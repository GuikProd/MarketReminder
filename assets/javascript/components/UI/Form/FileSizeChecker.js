export class FileSizeChecker {

    /**
     * @param inputId
     * @param elementToHideId
     * @param maxFileSize
     */
    constructor(inputId, elementToHideId, maxFileSize) {
        this.inputId = inputId;
        this.elementToHideId = elementToHideId;
        this.maxFileSize = maxFileSize;
    }

    handle() {
        let input = document.getElementById(this.inputId);
        let inputFiles = document.getElementById(this.inputId).files;

        input.addEventListener('change', function(e) {
            for (let i = 0, numFiles = inputFiles.length; i < numFiles; i++) {
                let file = inputFiles[i];

                if (file.size > 2000000) {
                    console.log(file);
                }
            }
        });
    }
}
