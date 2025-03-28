document.getElementById('loginForm').addEventListener('submit', function (event) {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === '' || password === '') {
        event.preventDefault();  // Prevent form submission
        alert('Please fill out both fields.');
    }
});

// Show/Hide password functionality
document.getElementById("showPassword").addEventListener("change", function() {
    const passwordField = document.getElementById("password");
    if (this.checked) {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
});

