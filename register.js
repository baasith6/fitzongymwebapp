// Show Password Functionality
document.getElementById('showPassword').addEventListener('change', function() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    
    if (this.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
    } else {
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
    }
});

// Password Validation
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    const passwordError = document.getElementById('passwordError');

    passwordError.textContent = '';

    if (!password.match(passwordPattern)) {
        passwordError.textContent = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one symbol.';
    }
});

// Password Confirm Validation
document.getElementById('confirmPassword').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const passwordMatchError = document.getElementById('passwordMatchError');

    passwordMatchError.textContent = '';

    if (password !== confirmPassword) {
        passwordMatchError.textContent = 'Passwords do not match!';
    }
});

// Form submission validation
document.getElementById('registerForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    const passwordError = document.getElementById('passwordError');
    const passwordMatchError = document.getElementById('passwordMatchError');

    let validPassword = true;

    passwordError.textContent = '';
    passwordMatchError.textContent = '';

    if (!password.match(passwordPattern)) {
        validPassword = false;
        passwordError.textContent = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one symbol.';
    }

    if (password !== confirmPassword) {
        validPassword = false;
        passwordMatchError.textContent = 'Passwords do not match!';
    }

    if (!validPassword) {
        event.preventDefault();
    }
});

// AJAX form submission
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Error: Email already exists")) {
            alert("Email already exists. Please use a different email.");
        } else if (data.includes("Registration successful")) {
            alert("Registration successful!");
            this.reset();
        } else {
            alert("An error occurred: " + data);
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
});
