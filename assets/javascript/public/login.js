import { MDCTextField } from '@material/textfield';
import { MDCRipple } from '@material/ripple';

new MDCTextField(document.querySelector('.login_username'));
new MDCTextField(document.querySelector('.login_password'));

new MDCRipple(document.querySelector('.login_button--cancel'));
new MDCRipple(document.querySelector('.login_button--submit'));
