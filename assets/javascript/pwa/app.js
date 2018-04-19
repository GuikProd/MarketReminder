if ('serviceWorker' in navigator) {
    navigator.serviceWorker
             .register('/build/sw.js')
             .then(function () {
                 console.log('Service Worker is registered.')
             })
             .catch(function (errors) {

             });
}

function askForNotification() {
    Notification.requestPermission(function (result) {
        if (result !== 'granted') {

        }

        new Notification('Hey from notification !');
    });
}

if ('Notification' in window) {
    askForNotification();
}
