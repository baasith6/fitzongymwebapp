// Show Password Functionality
document.getElementById('showPassword').addEventListener('change', function() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    
    // Toggle the type of the password fields between 'password' and 'text'
    if (this.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
    } else {
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
    }
});

document.getElementById('dob').addEventListener('change', function() {
    const dob = new Date(this.value);
    const today = new Date();
    const age = today.getFullYear() - dob.getFullYear();
    const month = today.getMonth() - dob.getMonth();
    
    if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    document.getElementById('age').textContent = `Age: ${age}`;
});

// Password Validation
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    const passwordError = document.getElementById('passwordError');
    
    passwordError.textContent = ''; // Clear previous error
    
    if (!password.match(passwordPattern)) {
        passwordError.textContent = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one symbol.';
    }
});

// Password Confirm Validation
document.getElementById('confirmPassword').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const passwordMatchError = document.getElementById('passwordMatchError');
    
    passwordMatchError.textContent = ''; // Clear previous error
    
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

    // Clear previous error messages
    passwordError.textContent = '';
    passwordMatchError.textContent = '';

    // Check if password meets the required criteria
    if (!password.match(passwordPattern)) {
        validPassword = false;
        passwordError.textContent = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one symbol.';
    }

    // Check if password and confirm password match
    if (password !== confirmPassword) {
        validPassword = false;
        passwordMatchError.textContent = 'Passwords do not match!';
    }

    // If validation fails, prevent form submission
    if (!validPassword) {
        event.preventDefault();
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const membershipSelect = document.getElementById('membership');
    
    // Fetch membership packages
    fetch('register.php?fetchMemberships=true')
        .then(response => response.json())
        .then(data => {
            data.forEach(membership => {
                const option = document.createElement('option');
                option.value = membership.mid;
                option.textContent = `${membership.name}`;
                option.dataset.price = membership.price; // Store price as data attribute
                membershipSelect.appendChild(option);
            });
        });

    // Display price on selection
    membershipSelect.addEventListener('change', function() {
        const selectedOption = membershipSelect.options[membershipSelect.selectedIndex];
        const price = selectedOption.dataset.price || '0.00';
        document.getElementById('membershipPrice').textContent = `Price: LKR${price}`;
    });
});

document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

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
            this.reset(); // Clear the form
        } else {
            alert("An error occurred: " + data);
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
});