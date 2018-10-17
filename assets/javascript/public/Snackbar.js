import { MDCSnackbar } from '@material/snackbar';
import MDCSnackbarFoundation from "@material/snackbar/foundation";

let footer = document.querySelector('footer');
let snackBar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));

let content = document.querySelector('#snackbar-content') !== null ? document.querySelector('#snackbar-content').textContent : null;

function displaySnackBar() {
    if (content !== null) {
        const dataObj = {
            message: content,
            actionText: 'Close',
            timeout: 4000,
            actionHandler: function () {
                snackBar.hide();
            }
        };
        snackBar.show(dataObj);
    }
}

window.addEventListener('load', function () {
    window.setTimeout(displaySnackBar(), 3000);
});

// Hide and display the main footer depending on the snackbar status.
snackBar.listen(MDCSnackbarFoundation.strings.SHOW_EVENT, function(e) {
    footer.hidden = true;
});

snackBar.listen(MDCSnackbarFoundation.strings.HIDE_EVENT, function(e) {
    footer.hidden = false;
});
