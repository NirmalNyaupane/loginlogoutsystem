//getting refrences of html
const name = document.getElementById("name");
const userName = document.getElementById("user-name");
const email=document.getElementById("email");
const password = document.getElementById("password");
const conPassword = document.getElementById("conpass");
const form = document.querySelector(".register-form");
/**
 * client side validataon
 * return true if validation is sucessful else false
  */

function validateData(){
       
        //validating name
        if(name.value==""){
            showError(name,"Name is required");
            return false;
        }else if(name.value.length<6){
            showError(name,"Minimum length of name should be six");
            return false;
        }else{
            sucessSubmission(name);
        }

        //validating username
        if(userName.value==""){
            showError(userName,"Username is required");
            return false;
        }else if(userName.value.includes(" ")){
            showError(userName,"Space is not allowed");
        }
        else if(userName.value.length<6){
            showError(userName,"Minimum length of username should be six");
            return false;
        }

        //validation email
        const emailReg = /^[a-zA-Z0-9.]{3,}@[a-zA-Z]{2,}[.][a-zA-Z]{2,}$/;
        if(email.value==""){
            showError(email,"Email is required");
            return false;
        }else if(!emailReg.test(email.value)){
            showError(email,"Invalid email address")
        }else{
            sucessSubmission(email);
        }

        //validation password
        // const passReg = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&-+=()])(?=\\S+$).{6, 20}$/;
        if(password.value==""){
            showError(password,"Password is required");
            return false;
        }else if(password.value.length<6){
            showError(password,"Minimum length of password is 6");
            return false;
        }else{
            sucessSubmission(password);
        }

        //validation of confirm password
        if(conPassword.value==""){
            showError(conPassword,"Confirm password is required");
            return false;
        }
        else if(conPassword.value!=password.value){
            showError(conPassword,"Password and password should be matched");
            return false;
        }else{
            sucessSubmission(conPassword);
        }

        return true;
    }

/**
 * Utility function to show the error if client side validation does not match 
 * @param {html element } element 
 * @param {Errormessage} msg 
 */

function showError(element, msg){
    const parent = element.parentElement;
    const error = parent.querySelector(".error");

    error.innerHTML = msg;
}

/**
 * Utility function to show the error if client side validation does not match 
 * @param {html element} element 
 */

function sucessSubmission(element){
    const parent = element.parentElement;
    const error = parent.querySelector(".error");

    error.innerHTML = "";
}

/**
 * When response comes from server
 * @param {JSON RESPONSE} response 
 */

//function that handles further procession of form

/**
 * When user clicks on submit button this function handles further resposne
 */
const processform = ()=>{
    const nameVal = name.value.trim();
    const userNameVal = userName.value.trim().toLowerCase();
    const emailVal = email.value.trim();
    const passwordVal = password.value.trim();
    const conPasswordVal = conPassword.value.trim();
    
    // for validate all data
    if(validateData()){

        //Making https request 
        const xhr = new XMLHttpRequest();
        xhr.open("POST","../php/register.php",true);
        xhr.responseType="json";
        xhr.onload=function(){
            if(this.readyState==4 && this.status==200){
                if(this.response.type == 1){
                    form.reset();
                    location.replace("login.html");
                }else if(this.response.type==2){
                    userName.nextElementSibling.innerHTML=this.response.err_name;
                }else if(this.response.type==3){
                    email.nextElementSibling.nextElementSibling.innerHTML=this.response.err_name;
                }
            }else{
                alert("Something wrong");
            }
        }
    
        //making javascript object inorder to send data in json format
        const obj = {
            name:nameVal,
            user:userNameVal,
            email:emailVal,
            password:passwordVal,
            conPassword:conPasswordVal
        }
    
        const jsonFile = JSON.stringify(obj);
        xhr.send(jsonFile);
    }


}

form.addEventListener("submit",(even)=>{
    even.preventDefault();
    processform();
});