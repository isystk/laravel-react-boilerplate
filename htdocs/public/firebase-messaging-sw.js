// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.3.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
  apiKey: "AIzaSyB8iz9_m2x4aeb7fwp4hGJ1u0_0KrvENDg",
  authDomain: "fcm-react-92bbf.firebaseapp.com",
  projectId: "fcm-react-92bbf",
  storageBucket: "fcm-react-92bbf.appspot.com",
  messagingSenderId: "530349365157",
  appId: "1:530349365157:web:45fade502a8c1a17117a4c",
  measurementId: "G-HBZTPGXD30"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

