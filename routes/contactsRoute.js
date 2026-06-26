import { Router } from 'express'
import { query } from 'express-validator'
import requestValidator from './../middlewares/requestValidator.js'
import sessionValidator from './../middlewares/sessionValidator.js'
import * as controller from './../controllers/contactsController.js'

const router = Router()

router.get('/', query('id').notEmpty(), requestValidator, sessionValidator, controller.getList)

export default router
