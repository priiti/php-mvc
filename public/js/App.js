// Show / Hide password for usersssss
function show() {
    const passwordInput = document.querySelector('#pwd');
    passwordInput.setAttribute('type', 'text');
}

function hide() {
    const passwordInput = document.querySelector('#pwd');
    passwordInput.setAttribute('type', 'password');
}

var pwdShown = 0;

if (document.querySelector('.showPwdCheckbox') !== null) {
    document.querySelector('.showPwdCheckbox').addEventListener('click', function () {
        if (pwdShown === 0) {
            show();
            pwdShown = 1;
        } else {
            pwdShown = 0;
            hide();
        }
    });
}

let a = 0;