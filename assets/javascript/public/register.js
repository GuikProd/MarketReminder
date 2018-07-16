import { MDCTextField } from '@material/textfield';
import { MDCRipple } from '@material/ripple';

new MDCTextField(document.querySelector('.register_username'));
new MDCTextField(document.querySelector('.register_email'));
new MDCTextField(document.querySelector('.register_password'));

new MDCRipple(document.querySelector('.register_button--submit'));
new MDCRipple(document.querySelector('.register_button--cancel'));
new MDCRipple(document.querySelector('.mdc-fab'));
