// Registration form -> profileImage name display
document.getElementById("register_profileImage").onchange = function () {
    document.getElementById("uploadFile").value = this.files[0].name;
};

// Form style

import { MDCFloatingLabel } from '@material/floating-label';
import { MDCTextField } from '@material/textfield';

const textField = new MDCTextField(document.querySelector('.mdc-text-field'));
const floatingLabel = new MDCFloatingLabel(document.querySelector('.mdc-floating-label'));
