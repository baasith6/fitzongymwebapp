<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Get the available classes from the database (this can be a predefined set of classes in your gym)
$classQuery = "SELECT * FROM classes";
$classResult = $conn->query($classQuery);

// Handle form submission for booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classId = $_POST['class_id'];
    $email = $_SESSION['email'];
    $selectedDate = $_POST['date']; // Get the selected date
    $selectedTime = $_POST['time']; // Get the selected time
    

    // Generate new booking ID (bid)
    $bidQuery = "SELECT bid FROM class_bookings ORDER BY bid DESC LIMIT 1"; // Get the last booking ID
    $bidResult = $conn->query($bidQuery);
    
    if ($bidResult->num_rows > 0) {
        $lastBid = $bidResult->fetch_assoc()['bid'];
        $bidNumber = (int) substr($lastBid, 1) + 1; // Extract number from last bid and increment
        $newBid = 'B' . str_pad($bidNumber, 3, '0', STR_PAD_LEFT); // Format new bid as B001, B002, etc.
    } else {
        $newBid = 'B001'; // If no previous booking exists, start with B001
    }

    // Check if the class is already booked for the selected date and time
    $checkQuery = "SELECT * FROM class_bookings WHERE email = '$email' AND cid = '$classId' AND date = '$selectedDate' AND time = '$selectedTime'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        $error = "You have already booked this class for the selected date and time.";
    } else {
        // Insert booking into the database with new bid
        $bookQuery = "INSERT INTO class_bookings (bid, email, cid, date, time) VALUES ('$newBid', '$email', '$classId', '$selectedDate', '$selectedTime')";

        if ($conn->query($bookQuery) === TRUE) {
            $success = "Class booked successfully! Your Booking ID is $newBid.";
        } else {
            $error = "Error booking the class. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Class</title>
    <link rel="stylesheet" href="book_class_style.css">
</head>
<body>
    <div class="container">
        <h1>Book a Class</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="book_class.php" method="POST">
            <div class="form-group">
                <label for="class_id">Select Class:</label>
                <select name="class_id" id="class_id" required>
                    <?php while ($class = $classResult->fetch_assoc()): ?>
                        <option value="<?php echo $class['cid']; ?>"><?php echo $class['class_name']; ?> - <?php echo $class['cid']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Select Date:</label>
                <input type="date" name="date" id="date" required>
            </div>

            <div class="form-group">
                <label for="time">Select Time:</label>
                <input type="time" name="time" id="time" required>
            </div>

            <button type="submit">Book Class</button>
        </form>
        <br>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
