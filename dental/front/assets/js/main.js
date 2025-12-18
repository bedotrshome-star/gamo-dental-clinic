// Main JavaScript file for Gamo Dental Clinic

document.addEventListener('DOMContentLoaded', function() {
    console.log('Gamo Dental System Loaded');

    // Example of client-side validation logic
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');

            if (!usernameInput.value.trim()) {
                alert('Please enter a username.');
                event.preventDefault();
                return;
            }

            if (!passwordInput.value.trim()) {
                alert('Please enter a password.');
                event.preventDefault();
                return;
            }
        });
    }
});
