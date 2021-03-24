import { createBrowserHistory } from 'history'
import { applyMiddleware, createStore } from 'redux'
import { routerMiddleware } from 'connected-react-router'
import createRootReducer from '../reducers'
import { persistReducer } from 'redux-persist'
import storage from 'redux-persist/lib/storage'
import thunk from 'redux-thunk'
import { composeWithDevTools } from 'redux-devtools-extension'

const persistConfig = {
  key: 'root',
  storage,
  blacklist: ['router'],
}

export const history = createBrowserHistory()

const persistedReducer = persistReducer(persistConfig, createRootReducer(history))

export default function configureStore(preloadedState) {
  // 開発環境の場合は、redux-devtools-extension を利用できるようにする
  // const composeEnhancer = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
  const enhancer =
    process.env.NODE_ENV === 'development'
      ? composeWithDevTools(applyMiddleware(thunk, routerMiddleware(history)))
      : applyMiddleware(thunk, routerMiddleware(history))

  const store = createStore(persistedReducer, preloadedState, enhancer)
  return store
}
