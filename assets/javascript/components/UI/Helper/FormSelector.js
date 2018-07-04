class FormSelector {
    selectElement(element) {
        return document.querySelector(element);
    }

    changeVisibility(element, visibility) {
        let el = document.querySelector(element);
        el.disabled = visibility;
    }
}
