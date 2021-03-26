import { combineReducers } from 'redux'
import { connectRouter } from 'connected-react-router'
import parts from './parts'
import auth from './auth'
import consts from './consts'
import stocks from './stocks'
import carts from './carts'
import likes from './likes'

const rootReducer = (history: any) =>
  combineReducers({
    parts,
    auth,
    consts,
    stocks,
    carts,
    likes,
    router: connectRouter(history),
  })

export default rootReducer
