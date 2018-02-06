// Registration form -> profileImage name display
document.getElementById("register_profileImage").onchange = function () {
    document.getElementById("uploadFile").value = this.files[0].name;
};
