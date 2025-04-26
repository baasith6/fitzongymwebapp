document.getElementById('loginForm').addEventListener('submit', function (event) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    // Check if fields are empty
    if (email === '' || password === '') {
        event.preventDefault();  // Stop form submit
        alert('Please fill out both Email and Password fields.');
        return;
    }

    // Validate email format (basic regex)
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        event.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }

    // Validate password minimum length
    if (password.length < 6) {
        event.preventDefault();
        alert('Password must be at least 6 characters.');
        return;
    }
});

// Show/Hide password functionality
document.getElementById("showPassword").addEventListener("change", function() {
    const passwordField = document.getElementById("password");
    passwordField.type = this.checked ? "text" : "password";
});


