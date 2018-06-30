import { LitElement, html } from '@polymer/lit-element';
import { MDCSnackbar, MDCSnackbarFoundation } from '@material/snackbar';

class HomeComponent extends LitElement {

    static get properties() {
        return {
            notification: String
        }
    }

    _render({notification, text}) {
        if (notification == 'yes') {
            const snackBar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));
            const dataObj = {
                message: 'Text',
                actionText: 'Undo',
                actionHandler: function () {
                    console.log('my cool function');
                }
            };
            snackBar.show(dataObj);

            return html`
                <div>Hello From Polymer !</div>
                <div class="mdc-snackbar mdc-snackbar--align-start"
                     aria-live="assertive"
                     aria-atomic="true"
                     aria-hidden="true">
                    <div class="mdc-snackbar__text"></div>
                    <div class="mdc-snackbar__action-wrapper">
                        <button type="button" class="mdc-snackbar__action-button"></button>
                    </div>
                </div>
            `;
        } else {
            return html`
                <div>Hello From Polymer !</div>
            `;
        }
    }
}

customElements.define('my-element', HomeComponent);
