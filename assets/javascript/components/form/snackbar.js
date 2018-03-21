import {MDCSnackbar} from '@material/snackbar';

const snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));

function displaySnackBar() {
    if (document.querySelector('#snackbar-content').textContent !== '') {
        const dataObj = {
            message: "Hello World !",
            actionText: 'Close',
            timeout: 4000,
            actionHandler: function () {
                console.log('my cool function');
            }
        };
        dataObj.message = document.querySelector('div#snackbar-content').textContent;
        snackbar.show(dataObj);
    }
}

window.addEventListener('load', function () {
    window.setTimeout(displaySnackBar(), 3000);
});
