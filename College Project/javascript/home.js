const logout = document.getElementById("logout");
const userDetail = document.getElementById("user-detail");
const deleteAccount = document.getElementById("delete-account");
// console.log(logout, userDetail, deleteAccount);

const userData = () => {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/home.php", true);
    xhr.responseType = "json";
    xhr.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.response);
            if (this.response == null || this.response.isloggin == false) {
                location.replace("login.html");
            } else {
                document.querySelector(".user-name").innerHTML = this.response.user.toUpperCase();
            }
        } else {
            alert("Something wrong");
        }
    }
}

const processData = () => {
    userData();
}

logout.addEventListener("click",()=>{
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/logout.php", true);
    // xhr.responseType = "json";
    xhr.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
                location.replace("login.html");
        } else {
            alert("Something wrong");
        }
    }
});

window.addEventListener("DOMContentLoaded", () => {
    processData();
});