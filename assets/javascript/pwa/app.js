import { MDCSnackbar } from '@material/snackbar';

if ('serviceWorker' in navigator) {
    navigator.serviceWorker
             .register('/main.js')
             .then(function () {
                 const snackBar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));
                 const dataObj = {
                     message: "Service worker registered !",
                     actionText: 'Undo',
                     actionHandler: function () {
                         console.log('my cool function');
                     }
                 };
                 snackBar.show(dataObj);

             })
             .catch(function (errors) {

             });
}

// function askForNotification() {
//     Notification.requestPermission(function (result) {
//         if (result !== 'granted') {
//
//         }
//
//         new Notification('Hey from notification !');
//     });
// }
//
// if ('Notification' in window) {
//     askForNotification();
// }
