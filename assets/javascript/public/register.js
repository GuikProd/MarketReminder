import { MDCTextField } from '@material/textfield';
import { MDCTextFieldHelperText } from '@material/textfield/helper-text';
import { MDCRipple } from '@material/ripple';

new MDCTextField(document.querySelector('.register_username'));
new MDCTextField(document.querySelector('.register_email'));
new MDCTextField(document.querySelector('.register_password'));

new MDCTextFieldHelperText(document.querySelector('register_username-helper-text'));
new MDCTextFieldHelperText(document.querySelector('register_email-helper-text'));
new MDCTextFieldHelperText(document.querySelector('register_password-helper-text'));

new MDCRipple(document.querySelector('.register_button--submit'));
new MDCRipple(document.querySelector('.register_button--cancel'));
new MDCRipple(document.querySelector('.mdc-fab'));
