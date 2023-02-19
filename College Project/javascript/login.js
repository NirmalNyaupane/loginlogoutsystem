const userName = document.getElementById("user-name");
const password = document.getElementById("password");
const form = document.querySelector(".login-form");

const isValidate=()=>{

    if(userName.value==""){
        showError(userName,"Username is empty");
        return false;
    }
    if(password.value==""){
        showError(password,"Password is empty");
        return false;
    }

    return true;
}

function showError(element, msg){
    const parent = element.parentElement;
    const error = parent.querySelector(".error");
    error.innerHTML = msg;
}

const processform = ()=>{
    const userNameVal = userName.value.trim().toLowerCase();
    const passwordVal = password.value.trim();

    if(isValidate()){
        const xhr = new XMLHttpRequest();
        xhr.open("POST","../php/login.php",true);
        xhr.responseType="json";
        xhr.onload=function(){
            if(this.readyState==4 && this.status==200){
                if(this.response.type==3){
                    location.replace("home.html");
                    return;
                }else if(this.response.type==1){
                    userName.nextElementSibling.innerText = this.response.err_name;

                }else if(this.response.type==2){
                    password.nextElementSibling.innerHTML=this.response.err_name;
                }

            }else{
                alert("Something wrong");
            }
        }

        const obj = {name:userNameVal,password:passwordVal};
        const data = JSON.stringify(obj);

        xhr.send(data);
    }
}



form.addEventListener("submit",(even)=>{
    even.preventDefault();
    processform();
});