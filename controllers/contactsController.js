import { getContactList } from './../whatsapp.js'
import response from './../response.js'

const getList = (req, res) => {
    return response(res, 200, true, '', getContactList(res.locals.sessionId))
}

export { getList }
