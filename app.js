import 'dotenv/config'
import express from 'express'
import nodeCleanup from 'node-cleanup'
import routes from './routes.js'
import { init, cleanup, logger } from './whatsapp.js'
import cors from 'cors'

const app = express()

// const host = process.env.WA_SERVER_HOST || undefined
// const port = parseInt(process.env.WA_SERVER_PORT ?? 8000)
const host = process.env.HOST || undefined
const port = parseInt(process.env.PORT ?? 3005)

app.use(cors())
app.use(express.urlencoded({ extended: true }))
app.use(express.json())
app.use('/', routes)

const listenerCallback = () => {
    init()
    logger.info(`Server is listening on http://${host ? host : 'localhost'}:${port}`)
}

if (host) {
    app.listen(port, host, listenerCallback)
} else {
    app.listen(port, listenerCallback)
}

nodeCleanup(cleanup)

export default app
