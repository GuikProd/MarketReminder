let addBtn = document.querySelector('#newItem');
let index = document.querySelector('#collection').getAttribute('data-index');

addBtn.addEventListener('click', function (e) {
    e.preventDefault();

    let prototype = document.querySelector('#collection').getAttribute('data-prototype');
    let newForm = prototype.replace(/__name__/g, index);

    document.querySelector('#collection').setAttribute('data-index', index);
    addBtn.insertAdjacentHTML('beforebegin', newForm);

    index++;
});

function removeEntry(entry) {

}

// let stockTagsCollection = null;
// let newItem = document.createElement("div");
// let eventElement = document.querySelector('#addNewItem');
//
// newItem.appendChild(eventElement);
//
// let collectionHandler = new CollectionHandler(
//     '#stock_creation_stockItems',
//     '.mdc-text-field__input',
//     '#addNewItem',
//     'div'
// );
// collectionHandler.handle();

//
// stockTagsCollection = document.querySelector('#collection');
//
// stockTagsCollection.setAttribute('index', stockTagsCollection.querySelectorAll('.mdc-text-field__input').length);
// stockTagsCollection.appendChild(newItem);
//
// let index = stockTagsCollection.getAttribute('index');
//
// eventElement.addEventListener('click', function (event) {
//     addNewItem(stockTagsCollection, newItem);
//     event.preventDefault();
//
//     return false;
// });
//
// if (index == 0) {
//     addNewItem(stockTagsCollection, newItem);
// } else {
//     var childrens = stockTagsCollection.children;
//
//     childrens.forEach(function(item) {
//         deleteItem(item)
//     });
// }
//
// function addNewItem(stockCollection, newItem) {
//     let prototype = stockCollection.getAttribute('data-prototype');
//     let form = prototype;
//
//     form = form.replace(/__name__/g, index).replace(/__name__label/g, 'Item N° ' + index);
//     newItem.insertAdjacentHTML('beforebegin', form);
//
//     index++;
//
//     deleteItem(form);
// }
//
// function deleteItem(newItem) {
//     let removeBtn = document.createElement('button');
//
//     removeBtn.type = "button";
//     removeBtn.class = "";
//     removeBtn.id = "removeItemBtn";
//
//     newItem.appendChild(removeBtn);
//
//     removeBtn.addEventListener('click', function(event) {
//         newItem.parentNode.removeChild(newItem);
//
//         event.preventDefault();
//
//         return false;
//     });
// }