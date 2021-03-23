import { combineReducers } from 'redux'
import { connectRouter } from 'connected-react-router'
import auth from './auth'
import consts from './consts'
import stocks from './stocks'
import carts from './carts'
import likes from './likes'

const rootReducer = (history) => combineReducers({
  auth,
  consts,
  stocks,
  carts,
  likes,
  router: connectRouter(history)
})

export default rootReducer
