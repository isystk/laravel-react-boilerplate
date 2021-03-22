import { combineReducers } from 'redux'
import { connectRouter } from 'connected-react-router'
import auth from './auth'
import consts from './consts'
import stocks from './stocks'
import likes from './likes'

const rootReducer = (history) => combineReducers({
  auth,
  consts,
  stocks,
  likes,
  router: connectRouter(history)
})

export default rootReducer
