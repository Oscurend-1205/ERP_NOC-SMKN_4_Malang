/**
 * Password Setup Logic - ERP NOC SMKN 4 Malang
 * Separated from resources/views/auth/password-setup.blade.php
 */

window.togglePassword = function(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility';
    }
}
