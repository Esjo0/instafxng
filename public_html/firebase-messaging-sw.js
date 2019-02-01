importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
  'messagingSenderId': '669992524558'
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  var notificationTitle = 'Instafxng Notification';
  var notificationOptions = {
    body: 'New Updates are available, kindly visit https://instafxng.com for more',
    icon: 'images/instafxng_logo.png',
    click_action: 'https://instafxng.com'
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
