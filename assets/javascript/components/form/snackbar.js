import {MDCSnackbar} from '@material/snackbar';

const snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));

if (snackbar.value !== '') {
    snackbar.show();
}
