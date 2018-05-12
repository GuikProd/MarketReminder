import { LitElement, html } from '@polymer/lit-element';

class HomeComponent extends LitElement {

    static get properties() {
        return {
            foo: String,
        }
    }

    _render(props) {
        return html`
            <div>Hello From Polymer !</div>
        `;
    }
}

customElements.define('my-element', new HomeComponent);
