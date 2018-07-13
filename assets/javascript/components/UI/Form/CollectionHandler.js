/**
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
export class CollectionHandler
{
    /**
     * Constructor.
     *
     * @param collectionSelector  The selector of the element which contain the prototype.
     * @param inputSelector       The selector of the input to count.
     * @param eventElement        The selector of the element which gonna trigger the addition.
     */
    constructor(collectionSelector, inputSelector, eventElement) {
        this.collectionSelector = collectionSelector;
        this.inputSelector = inputSelector;
        this.eventElement = eventElement;
    }

    /**
     * Allow to handle the whole form.
     */
    handle() {
        let collection = document.querySelector(this.collectionSelector);
        let eventElement = document.querySelector(this.eventElement);
        let index =  collection.querySelectorAll(this.inputSelector).length;

        if (index == 0) {
            this.addNewEntry(collection, index);
        }
    }

    /**
     * Allow to add a new entry in the Collection.
     *
     * @param container The container which contain the whole collection.
     * @param index     The index of the input count.
     */
    addNewEntry(container, index) {
        let template = container.getAttribute('data-prototype').replace(/__name__/g, index);
        let element = document.createElement('div');
        element.innerHTML = template;

        this.removeEntry(element);

        container.appendChild(element);
    }

    /**
     * Allow to remove an entry.
     *
     * @param prototype
     */
    removeEntry(prototype) {
        let removeBtn = document.createElement('btn');
        removeBtn.id = 'removeBtn';

        prototype.appendChild(removeBtn);

        removeBtn.addEventListener('click', function(event) {
            prototype.parentNode.removeChild(prototype);
            event.preventDefault();
        });
    }
}
