// Show / Hide password for user
function show() {
    const passwordInput = document.querySelector('#pwd');
    passwordInput.setAttribute('type', 'text');
}

function hide() {
    const passwordInput = document.querySelector('#pwd');
    passwordInput.setAttribute('type', 'password');
}

let pwdShown = 0;
document.querySelector('.showPwdCheckbox').addEventListener('click', function () {
   if (pwdShown === 0) {
       show();
       pwdShown = 1;
   } else {
       pwdShown = 0;
       hide();
   }
});