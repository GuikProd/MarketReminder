function handleFileSize(formId, formBtnId) {

    for (var i = 0, numFiles = this.files.length; i < numFiles; i++) {
        let file = this.files[i];

        if (file.size > 2000000) {
            formBtn.disabled = true;
            let help = document.createElement('p');
            help.textContent = 'Cette image dépasse la limite autorisé (2mo).';
            profileImageHelper.appendChild(help);
        } else {
            formBtn.disabled = false;
        }
    }
}