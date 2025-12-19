/* eslint-disable prefer-arrow-callback */
/* eslint-disable no-unused-vars */
import { isSessionExists, createSession, getSession, deleteSession } from './../whatsapp.js'
import response from './../response.js'
import * as fs from 'fs'

const find = (req, res) => {
    response(res, 200, true, 'Session found.')
}

const findorFail = (req, res) => {
    response(res, 200, true, 'Session found.')
}

const status = (req, res) => {
    fs.readFile(`sessions/md_${res.locals.sessionId}/creds.json`, function (err, data) {
        if (err) {
            const states = ['connecting', 'connected', 'disconnecting', 'disconnected']

            const session = getSession(res.locals.sessionId)
            let state = states[session.ws.readyState]

            state =
                state === 'connected' &&
                typeof (session.isLegacy ? session.state.legacy.user : session.user) !== 'undefined'
                    ? 'authenticated'
                    : state

            response(res, 403, true, '', { status: state, valid_session: false })
        } else {
            const states = ['connecting', 'connected', 'disconnecting', 'disconnected']

            const session = getSession(res.locals.sessionId)
            let state = states[session.ws.readyState]

            state =
                state === 'connected' &&
                typeof (session.isLegacy ? session.state.legacy.user : session.user) !== 'undefined'
                    ? 'authenticated'
                    : state

            let rawdata = fs.readFileSync(`sessions/md_${res.locals.sessionId}/creds.json`)
            let userdata = JSON.parse(rawdata)

            response(res, 200, true, '', { status: state, valid_session: true, userinfo: userdata.me })
        }
    })
}

const add = async (req, res) => {
    const { id, isLegacy, phoneNumber } = req.body

    console.log('sessions/add called with:', { id, isLegacy, phoneNumber });

    // If session exists, delete it first (especially for phone pairing)
    if (isSessionExists(id)) {
        console.log('Session exists, deleting old session first...');
        try {
            const existingSession = getSession(id);
            if (existingSession) {
                try {
                    await existingSession.logout();
                } catch (err) {
                    console.log('Logout error (ignored):', err.message);
                }
            }
            deleteSession(id, isLegacy === 'true');
            console.log('Old session deleted successfully');
        } catch (err) {
            console.error('Error deleting old session:', err);
        }
    }

    console.log('Creating new session with phoneNumber:', phoneNumber);
    createSession(id, isLegacy === 'true', res, phoneNumber || null)
}

const del = async (req, res) => {
    const { id } = req.params
    const session = getSession(id)

    try {
        await session.logout()
    } catch {
    } finally {
        deleteSession(id, session.isLegacy)
    }

    response(res, 200, true, 'The session has been successfully deleted.')
}

export { find, status, add, del }
