import { MDCTextField } from '@material/textfield';
import { MDCTextFieldHelperText } from '@material/textfield/helper-text';
import { MDCRipple } from '@material/ripple';

new MDCTextField(document.querySelector('.stock_creation_title'));
new MDCTextField(document.querySelector('.stock_creation_status'));
new MDCTextField(document.querySelector('.stock_creation_tags'));

new MDCTextFieldHelperText(document.querySelector('.stock_creation_title-helper-text'));
new MDCTextFieldHelperText(document.querySelector('.stock_creation_tags-helper-text'));

new MDCRipple(document.getElementById('stock_creation-button_newItem'));
new MDCRipple(document.getElementById('stock_creation-button_submit'));
