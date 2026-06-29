import { Router } from 'express'
import sessionsRoute from './routes/sessionsRoute.js'
import chatsRoute from './routes/chatsRoute.js'
import groupsRoute from './routes/groupsRoute.js'
import response from './response.js'

import contactsRoute from './routes/contactsRoute.js'

const router = Router()

router.use('/sessions', sessionsRoute)
router.use('/chats', chatsRoute)
router.use('/groups', groupsRoute)
router.use('/contacts', contactsRoute)

router.all('*', (req, res) => {
    console.log(`[404] Method: ${req.method} URL: ${req.originalUrl}`);
    response(res, 404, false, 'The requested url cannot be found.')
})

export default router
