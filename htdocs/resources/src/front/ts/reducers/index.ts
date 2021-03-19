import { combineReducers } from 'redux'
import { reducer as form } from 'redux-form'
import * as auth from './auth'
import consts from './consts'
import stocks from './stocks'
import likes from './likes'

export default combineReducers({ ...auth, consts, stocks, likes, form })
