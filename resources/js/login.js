/**
 * Login Logic & Theme Configuration - ERP NOC SMKN 4 Malang
 * Separated from resources/views/auth/login.blade.php
 */

// Inject configuration to Tailwind CDN
if (window.tailwind) {
    window.tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#3b3fbd",
                    "background-light": "#f0f4f9",
                    "background-dark": "#0f172a",
                },
                fontFamily: {
                    display: ["Inter", "sans-serif"],
                },
                borderRadius: {
                    DEFAULT: "0.5rem",
                    'card': "2rem",
                },
            },
        },
    };
}

window.togglePassword = function() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('password-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility';
    }
}
