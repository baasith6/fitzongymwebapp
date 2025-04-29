<?php
session_start();
include("dbconfig.php");

// Secure staff access
if (!isset($_SESSION['email']) || ($_SESSION['user_type'] !== 'management' && $_SESSION['user_type'] !== 'admin')) {
    echo "<p>Access denied.</p>";
    exit;
}

// Fetch customers
$customersResult = $conn->query("SELECT * FROM customers");
?>

<div class="container">
    <h1>Manage Customer Profiles</h1>

    <table class="datatable">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($customer = $customersResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['contact_number']); ?></td>
                    <td>
                        <button class="edit-btn" 
                                data-firstname="<?php echo $customer['first_name']; ?>" 
                                data-lastname="<?php echo $customer['last_name']; ?>" 
                                data-email="<?php echo $customer['email']; ?>" 
                                data-contact="<?php echo $customer['contact_number']; ?>">
                                Edit
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Customer</h2>
        <form id="editCustomerForm">
            <input type="hidden" name="old_email" id="old_email">

            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div class="form-group">
                <label>New Email:</label>
                <input type="email" name="new_email" id="new_email" required>
            </div>
            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="contact_number" id="contact_number" required>
            </div>

            <button type="submit">Update Customer</button>
        </form>
    </div>
</div>

<script>
// Modal Handling
const modal = document.getElementById('editModal');
const closeBtn = document.querySelector('.close');

document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('first_name').value = this.dataset.firstname;
        document.getElementById('last_name').value = this.dataset.lastname;
        document.getElementById('new_email').value = this.dataset.email;
        document.getElementById('old_email').value = this.dataset.email;
        document.getElementById('contact_number').value = this.dataset.contact;
        modal.style.display = "block";
    });
});

closeBtn.onclick = () => { modal.style.display = "none"; };
window.onclick = event => { if (event.target == modal) modal.style.display = "none"; };

// Handle update customer
document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('update_customer_partial.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Unexpected Error!',
            text: 'Please try again later.'
        });
    });
});
</script>
