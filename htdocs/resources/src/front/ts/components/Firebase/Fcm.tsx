import React, { FC, useState } from 'react'
import { useDispatch } from 'react-redux'
import Modal from '../Commons/Modal'

import firebase from 'firebase'
import { showOverlay } from '../../actions'

const Fcm: FC = () => {
  const dispatch = useDispatch()
  const [token, setToken] = useState('')
  const [notification, setNotification] = useState({ body: '' })

  // ServiceWorkerをルート以外に配置する場合
  // useEffect(() => {
  //   // Service Worker explicit registration to explicitly define sw location at a path
  //   ;async () => {
  //     try {
  //       await navigator.serviceWorker.register('/hoge/firebase-messaging-sw.js')
  //     } catch (error) {
  //       console.error(error)
  //     }
  //   }
  // }, [])

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  if (!firebase.apps.length) {
    const firebaseConfig = {
      apiKey: 'AIzaSyB8iz9_m2x4aeb7fwp4hGJ1u0_0KrvENDg',
      authDomain: 'fcm-react-92bbf.firebaseapp.com',
      projectId: 'fcm-react-92bbf',
      storageBucket: 'fcm-react-92bbf.appspot.com',
      messagingSenderId: '530349365157',
      appId: '1:530349365157:web:45fade502a8c1a17117a4c',
      measurementId: 'G-HBZTPGXD30',
    }
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig)
    firebase.analytics()
  }

  // メッセージング オブジェクトの取得
  const messaging = firebase.messaging()

  // プッシュ通知を受信した場合
  messaging.onMessage(payload => {
    console.log('Message received. ', payload)
    setNotification(payload.notification)
    dispatch(showOverlay())
  })

  const YOUR_PUBLIC_VAPID_KEY_HERE =
    'BDtbuB12fVdSgSE3T4U6a1vc2be2d0ahiB-ycZONrOZ2oVKe3q0dAV0rmbyJptmwqLIWfF1U14a07hVzLDCMkJE'

  // アプリにウェブ認証情報を設定する
  messaging.getToken({ vapidKey: YOUR_PUBLIC_VAPID_KEY_HERE })

  const getToken = () =>
    messaging
      .getToken({ vapidKey: YOUR_PUBLIC_VAPID_KEY_HERE })
      .then(currentToken => {
        if (currentToken) {
          // Send the token to your server and update the UI if necessary
          // ...
          console.log('Success!!', currentToken)
          setToken(currentToken)
        } else {
          // Show permission request UI
          console.log('No registration token available. Request permission to generate one.')
          // ...
        }
      })
      .catch(err => {
        console.log('An error occurred while retrieving token. ', err)
        // ...
      })

  return (
    <>
      <div className="contentsArea">
        <div className="card">
          <div className="card-body">
            <div className="contentsArea">
              <p>
                <input type="button" value="トークンを取得する" onClick={getToken} />
              </p>
              <p>{token}</p>
            </div>
          </div>
        </div>
      </div>
      <Modal>
        <div style={{ padding: '50px 20px' }}>
          <div className="card">
            <div className="card-body">
              <p>{notification.body}</p>
            </div>
          </div>
        </div>
      </Modal>
    </>
  )
}

export default Fcm
