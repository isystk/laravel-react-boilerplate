import * as React from 'react'
import * as ReactDom from 'react-dom'
import { persistStore, persistReducer, persistCombineReducers } from 'redux-persist'
import configureStore, { history } from './store/configureStore'
import storage from 'redux-persist/lib/storage';
import { PersistGate } from 'redux-persist/integration/react'
import { Provider } from 'react-redux'
import axios from 'axios'

import reducers from './reducers'
import ReactRoot from './ReactRoot'

import 'bootstrap'
import 'heic2any'

const persistConfig = {
  key: 'root',
  version: 1,
  storage,
};
// const history = createBrowserHistory()
// const rootReducer  = reducers(history)
// const persistedReducer = persistCombineReducers(persistConfig, {...rootReducer});

const store = configureStore({})
const pstore = persistStore(store)

const render = (props) => {
  ReactDom.render(
    <Provider store={store}>
      <PersistGate loading={<p>loading...</p>} persistor={pstore}>
        <ReactRoot history={history} responseSession={props} />
      </PersistGate>
    </Provider>,
    document.getElementById('react-root'),
  )
}

function authSession()
{
  const params = new URLSearchParams();
  const url = '/session';
  axios.post(url,params)
  .then((response)=>{
      render(response.data)
  })
}

authSession()
