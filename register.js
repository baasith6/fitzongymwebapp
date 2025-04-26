// Show Password Functionality
document.getElementById('showPassword').addEventListener('change', function() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    passwordField.type = confirmPasswordField.type = this.checked ? 'text' : 'password';
});

// Form Validation and AJAX Submission
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Stop normal form submission

    const firstName = document.querySelector('input[name="firstName"]').value.trim();
    const lastName = document.querySelector('input[name="lastName"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const contactNumber = document.querySelector('input[name="contactNumber"]').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const passwordError = document.getElementById('passwordError');
    const passwordMatchError = document.getElementById('passwordMatchError');

    passwordError.textContent = '';
    passwordMatchError.textContent = '';

    let valid = true;

    // Email format validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        valid = false;
    }

    // Contact number validation (only digits, 10-15 numbers)
    const contactPattern = /^[0-9]{10,15}$/;
    if (!contactPattern.test(contactNumber)) {
        alert('Please enter a valid contact number (10-15 digits).');
        valid = false;
    }

    // Password strength validation
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    if (!password.match(passwordPattern)) {
        passwordError.textContent = 'Password must be at least 8 characters, and contain uppercase, lowercase, number, and symbol.';
        valid = false;
    }

    // Confirm password validation
    if (password !== confirmPassword) {
        passwordMatchError.textContent = 'Passwords do not match!';
        valid = false;
    }

    if (!valid) {
        return; // Stop AJAX if form invalid
    }

    // If all validation passed, proceed with AJAX
    const formData = new FormData(this);

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // For debugging

        if (data.includes('Email or Contact Number already registered')) {
            alert("Email or contact number already exists. Please use different details.");
        } else if (data.includes('Registration successful')) {
            alert("Registration successful!");
            this.reset();
        } else if (data.includes('Passwords do not match')) {
            alert("Passwords do not match.");
        } else {
            alert("An unknown error occurred: " + data);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("A network error occurred. Please try again.");
    });
});
