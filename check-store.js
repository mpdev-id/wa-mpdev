import { makeInMemoryStore } from '@whiskeysockets/baileys';
import pino from 'pino';

const store = makeInMemoryStore({ logger: pino({ level: 'silent' }) });
console.log('Store properties:', Object.keys(store));
console.log('loadMessages type:', typeof store.loadMessages);

try {
  const result = await store.loadMessages('test@s.whatsapp.net', 10, null);
  console.log('loadMessages call success, result:', result);
} catch (e) {
  console.error('loadMessages call error:', e.message, e.stack);
}
