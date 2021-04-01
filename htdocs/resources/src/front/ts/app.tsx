import * as React from 'react'
import * as ReactDom from 'react-dom'
import { persistStore } from 'redux-persist'
import myConfigureStore, { history } from './store/configureStore'
import { PersistGate } from 'redux-persist/integration/react'
import { Provider } from 'react-redux'
import axios from 'axios'

import ReactRoot from './ReactRoot'

const store = myConfigureStore()
const pstore = persistStore(store)

const render = (props: string) => {
  ReactDom.render(
    <Provider store={store}>
      <PersistGate loading={<p>loading...</p>} persistor={pstore}>
        <ReactRoot history={history} responseSession={props} />
      </PersistGate>
    </Provider>,
    document.getElementById('react-root'),
  )
}

const authSession = () => {
  const params = new URLSearchParams()
  const url = '/session'
  axios.post(url, params).then(response => {
    render(response.data)
  })
}

authSession()
