/**
 * Created by user on 22/01/2019.
 */
//<div id="token"></div>
//<div id="msg"></div>
//    <div id="notis"></div>
//    <div id="err"></div>
//MsgElem = document.getElementById("msg")
//TokenElem = document.getElementById("token")
//NotisElem = document.getElementById("notis")
//ErrElem = document.getElementById("err")
// Initialize Firebase
// TODO: Replace with your project's customized code snippet
// Initialize Firebase
var config = {
    apiKey: "AIzaSyCM33UOcaZTJ9H35X7YRmI09zG7gX0YHH0",
    authDomain: "instafxng-notification.firebaseapp.com",
    databaseURL: "https://instafxng-notification.firebaseio.com",
    projectId: "instafxng-notification",
    storageBucket: "instafxng-notification.appspot.com",
    messagingSenderId: "669992524558"

};
firebase.initializeApp(config);


const messaging = firebase.messaging();
messaging
    .requestPermission()
    .then(function () {
        //MsgElem.innerHTML = "Notification permission granted.";
        console.log("Notification permission granted.");

        // get the token in the form of promise
        return messaging.getToken()
    })
    .then(function(token) {
        TokenElem.innerHTML = "token is : " + token
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET", "../logic/pass_notification_token.php?token="+token+"&type=1", true);
        xmlhttp.send();
    })
    .catch(function (err) {
        //ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
        console.log("Unable to get permission to notify.", err);
    });

messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    //NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload)
    document.getElementById('page_reloader').style.display = 'block';
});