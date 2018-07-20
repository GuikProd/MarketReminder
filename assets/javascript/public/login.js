import { MDCTextField } from '@material/textfield';
import { MDCNotchedOutline } from '@material/notched-outline';
import { MDCRipple } from '@material/ripple';

new MDCTextField(document.querySelector('.login_username'));
new MDCTextField(document.querySelector('.login_password'));

new MDCRipple(document.querySelector('.login_button--cancel'));
new MDCRipple(document.querySelector('.login_button--submit'));

new MDCNotchedOutline(document.querySelector('.login_username'));
new MDCNotchedOutline(document.querySelector('.login_password'));
