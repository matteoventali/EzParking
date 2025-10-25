function emailValidation(email){
    const standard = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  //standard email something@something.something
    return standard.test(email);
}

function passwordValidation(password){
    if(password.length < 8 ){
        return false;
    }
}

function phoneValidation(phone){
    const standard = /^[0-9]{10}$/; //only numbers
    return standard.test(phone);
    
}

function loginValidation(){
    const form = document.getElementById("login-form");
    if (!form) return;

    form.addEventListener("submit", function (e){
        const email = document.getElementById("email").value ;
        //const psw = document.getElementById("password").value;
        
        if(!emailValidation(email)){
            alert("Email not valid");
            e.preventDefault(); //block form to not send the info
            return;
        }

    });
}

function registerValidation(){
    const form = document.getElementById("register-form");
    if(!form) return;

    form.addEventListener("submit", function (e){
        const email = document.getElementById("email").value;
        const psw = document.getElementById("password").value;
        const phone = document.getElementById("phone").value;

        if(!emailValidation(email)){
            alert("Email not valid");
            e.preventDefault();
            return;
        }

        if(passwordValidation(psw)==false){
            alert("Password too short, at least 8 characters");
            e.preventDefault();
            return;
        }

        if(!phoneValidation(phone)){
            alert("Phone number not valid, must be ten numbers");
            e.preventDefault();
            return;
        }
    })
}

function editValidation(){
    const form = document.getElementById("edit-form");
    if(!form) return;

    form.addEventListener("submit", function (e){
        const email = document.getElementById("email").value;
        const new_psw = document.getElementById("new").value;
        const confirm_psw = document.getElementById("confirm").value;
        const phone = document.getElementById("phone").value;

        if(!emailValidation(email)){
            alert("Email not valid");
            e.preventDefault();
            return;
        }

        if(passwordValidation(new_psw)==false){
            alert("Password too short, at least 8 characters");
            e.preventDefault();
            return;
        }

        if(new_psw !== confirm_psw){
            alert("Passwords are not the same");
            e.preventDefault();
            return;
        }

        if(!phoneValidation(phone)){
            alert("Phone number not valid, must be ten numbers");
            e.preventDefault();
            return;
        }        
    })
}

document.addEventListener("DOMContentLoaded", () => {
    registerValidation();
    loginValidation();
    editValidation();
});