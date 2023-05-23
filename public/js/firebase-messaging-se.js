// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: 'AIzaSyCQWN3FQTl2p8NZmdoIoqWNLzlDVXk8tbs',
    authDomain: 'ypay-5949b.firebaseapp.com',
    databaseURL: 'https://ypay-5949b.firebaseio.com',
    projectId: "ypay-5949b",
    storageBucket: "ypay-5949b.appspot.com",
    messagingSenderId: "1029859183913",
    appId: "1:1029859183913:web:a85c5dfafbfa9355827b38",
    measurementId: "G-XVY0RT1CLY"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});