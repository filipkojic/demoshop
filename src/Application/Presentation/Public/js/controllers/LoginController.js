/**
 * LoginController class to manage the content of the "Login" page.
 */
class LoginController {
    /**
     * Creates an instance of LoginController.
     * @param {string} formId - The ID of the login form.
     */
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.usernameInput = this.form.querySelector('#username');
        this.passwordInput = this.form.querySelector('#password');
        this.attachEventListeners();
    }

    /**
     * Attaches event listeners to the form.
     */
    attachEventListeners() {
        this.form.addEventListener('submit', (event) => this.validate(event));
    }

    /**
     * Validates the login form inputs.
     * @param {Event} event - The form submission event.
     */
    validate(event) {
        let valid = true;
        let errorMessage = '';

        // Validate username input
        if (this.usernameInput.value.trim() === '') {
            errorMessage += 'Username cannot be empty!\n\n';
            valid = false;
        }

        // Validate password input
        const password = this.passwordInput.value;
        if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*]/.test(password)) {
            errorMessage += 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character!\n';
            valid = false;
        }

        // If any validation fails, prevent the form from being submitted and show an alert
        if (!valid) {
            event.preventDefault();
            alert(errorMessage);
        }
    }

    /**
     * Static method to initialize the LoginController.
     * It searches for a form with the provided ID and initializes the class.
     */
    static init(formId) {
        new LoginController(formId);
    }
}

// Automatically initialize the LoginController when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    LoginController.init('loginForm');
});
