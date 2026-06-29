process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
import 'dotenv/config'
import express from 'express'
import nodeCleanup from 'node-cleanup'
import routes from './routes.js'
import { init, cleanup, logger } from './whatsapp.js'
import cors from 'cors'
import { Server } from 'socket.io'
import http from 'http'
import { setIo } from './socket.js'

const app = express()

// const host = process.env.WA_SERVER_HOST || undefined
// const port = parseInt(process.env.WA_SERVER_PORT ?? 8000)
const host = process.env.HOST || '127.0.0.1'
const port = parseInt(process.env.PORT ?? 3005)

app.use(cors())
app.use(express.urlencoded({ extended: true }))
app.use(express.json())
app.use('/', routes)

const listenerCallback = () => {
    init()
    logger.info(`Server is listening on http://${host ? host : 'localhost'}:${port}`)
}

const server = http.createServer(app)
const io = new Server(server, {
    cors: {
        origin: '*',
    }
})
setIo(io)

if (host) {
    server.listen(port, host, listenerCallback)
} else {
    server.listen(port, listenerCallback)
}

nodeCleanup(cleanup)

export default app
// trigger restart
// trigger restart 2
// trigger restart 3
// trigger restart 4
