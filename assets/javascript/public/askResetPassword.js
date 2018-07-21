import { MDCTextField } from '@material/textfield';
import { MDCNotchedOutline } from '@material/notched-outline';
import { MDCRipple } from '@material/ripple';

new MDCTextField(document.querySelector('.ask_reset_password_username'));
new MDCTextField(document.querySelector('.ask_reset_password_email'));

new MDCRipple(document.querySelector('.ask_reset_password_button--cancel'));
new MDCRipple(document.querySelector('.ask_reset_password_button--submit'));

new MDCNotchedOutline(document.querySelector('.ask_reset_password_username'));
new MDCNotchedOutline(document.querySelector('.ask_reset_password_email'));
