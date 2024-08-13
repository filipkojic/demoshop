/**
 * Initializes the login form validation when the document is fully loaded.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Initialize variables for the form and input fields
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    // Add event listener for the form's submit event
    form.addEventListener('submit', function (event) {
        let valid = true;
        let errorMessage = '';

        // Validate username input
        if (usernameInput.value.trim() === '') {
            errorMessage += 'Username cannot be empty!\n\n';
            valid = false;
        }

        // Validate password input - must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character
        const password = passwordInput.value;
        if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*]/.test(password)) {
            errorMessage += 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character!\n';
            valid = false;
        }

        // If any validation fails, prevent the form from being submitted and show an alert
        if (!valid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });
});
