const initLogin = () => {
    let contentLeft = document.querySelector('#contentLeft');
    document.querySelector('#spacerContentLeft').className = "col-4";
    contentLeft.style.minWidth='40%';
    contentLeft.style.maxWidth='40%';
    document.querySelector('#contentRight').className = 'login-contentRight';
    document.querySelector('#loginRegisterToggleButton').addEventListener('click', toggleLoginRegister);
    document.querySelector('#loginRegisterConfirmButton').style.left = '20%';
    document.querySelector('#loginRegisterToggleButton').style.right= '20%';

}
const setLoginScreen = () => {
    let newContentLeft = document.createElement('div');
    newContentLeft.className = "col-4 bg-light rounded-5 my-2 py-2 align-self-left scrollable-contentleft";
    document.querySelector('#spacerContentLeft').className = "col-3";
    document.querySelector('#contentRight').hidden = 'hidden';
    document.querySelector('#contentLeft').replaceWith(newContentLeft);
    console.log(document.querySelector('#contentLeft').className);
}

const toggleLoginRegister = (event) => {
    let buttonUserName = document.querySelector('#inputUsername');
    let buttonEmailValidate = document.querySelector('#inputEmailValidate');
    let buttonPasswordValidate = document.querySelector('#inputPasswordValidate');
    let labelUserName = document.querySelector('#labelInputUsername');
    let labelEmailValidate = document.querySelector('#labelInputEmailValidate');
    let labelPasswordValidate = document.querySelector('#labelInputPasswordValidate');
    let buttonConfirm = document.querySelector('#loginRegisterConfirmButton');

    if (event.target.innerHTML === 'go to Register') {
        buttonUserName.type = 'text';
        buttonEmailValidate.type = 'email';
        buttonPasswordValidate.type = 'password';
        labelUserName.style.visibility = "visible";
        labelEmailValidate.style.visibility = "visible";
        labelPasswordValidate.style.visibility = "visible";
        buttonConfirm.name = 'registerUser';
        buttonConfirm.innerHTML = 'Sign Up';
        event.target.innerHTML = 'go to Login';
    } else {
        buttonUserName.type = 'hidden';
        buttonEmailValidate.type = 'hidden';
        buttonPasswordValidate.type = 'hidden';
        labelUserName.style.visibility = "hidden";
        labelEmailValidate.style.visibility = "hidden";
        labelPasswordValidate.style.visibility = "hidden";
        buttonConfirm.name = 'loginUser';
        buttonConfirm.innerHTML = 'Sign In';
        event.target.innerHTML = 'go to Register';
    }
}
