import { combineReducers } from 'redux'
import { connectRouter } from 'connected-react-router'
import { reducer as form } from 'redux-form'
import * as auth from './auth'
import consts from './consts'
import stocks from './stocks'
import likes from './likes'

const rootReducer = (history) => combineReducers({
  ...auth,
  router: connectRouter(history)
})

export default rootReducer
