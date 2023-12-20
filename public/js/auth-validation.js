document.addEventListener('DOMContentLoaded', function () {
    
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validatePasswordLength(password) {
        return password.length >= 7;
    }

    function validatePasswordMatch(password, confirmPassword) {
        return password === confirmPassword;
    }

    function updateErrorMessage(elementId, errorMessage) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = errorMessage;
    }


    function handleFormSubmission(event) {
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        updateErrorMessage('email_error', '');
        updateErrorMessage('password_error', '');
        updateErrorMessage('match_error', '');

  
        if (!validateEmail(email)) {
            updateErrorMessage('email_error', 'Invalid email format');
            event.preventDefault();
        }

        if (!validatePasswordLength(password)) {
            updateErrorMessage('password_error', 'Password must be at least 7 characters long');
            event.preventDefault();
        }


        if (!validatePasswordMatch(password, confirmPassword)) {
            updateErrorMessage('match_error', 'Passwords do not match');
            event.preventDefault();
        }
    }

    const form = document.getElementById('form');
    form.addEventListener('submit', handleFormSubmission);
});
