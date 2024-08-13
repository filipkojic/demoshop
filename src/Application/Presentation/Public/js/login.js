/**
 * Initializes the login form validation when the document is fully loaded.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Initialize variables for the form and input fields
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const usernameError = document.getElementById('usernameError');
    const passwordError = document.getElementById('passwordError');

    // Add event listener for the form's submit event
    form.addEventListener('submit', function (event) {
        // Reset error messages display
        usernameError.style.display = 'none';
        passwordError.style.display = 'none';

        let valid = true;

        // Validate username input
        if (usernameInput.value.trim() === '') {
            usernameError.textContent = 'Username cannot be empty!';
            usernameError.style.display = 'block';
            valid = false;
        }

        // Validate password input - must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character
        const password = passwordInput.value;
        if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*]/.test(password)) {
            passwordError.textContent = 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character!';
            passwordError.style.display = 'block';
            valid = false;
        }

        // If any validation fails, prevent the form from being submitted
        if (!valid) {
            event.preventDefault();
        }
    });
});
