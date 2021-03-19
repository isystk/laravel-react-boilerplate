import * as React from 'react'
import * as ReactDom from 'react-dom'
import { createStore, applyMiddleware } from 'redux'
import { persistStore } from 'redux-persist'
import { PersistGate } from 'redux-persist/integration/react'
import { Provider } from 'react-redux'
import thunk from 'redux-thunk'
import { composeWithDevTools } from 'redux-devtools-extension'
import axios from 'axios'

import reducers from './reducers'
import ReactRoot from './ReactRoot'

import 'bootstrap'
import 'heic2any'

// 開発環境の場合は、redux-devtools-extension を利用できるようにする
const enhancer =
  process.env.NODE_ENV === 'development' ? composeWithDevTools(applyMiddleware(thunk)) : applyMiddleware(thunk)
const store = createStore(reducers, enhancer)
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
