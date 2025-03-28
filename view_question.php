<?php
// Include the database connection file
include("dbconfig.php");

// Fetch data from the "questions" table
$query = "SELECT name, email, message, date FROM questions ORDER BY date DESC";
$result = $conn->query($query);

// Check if there are records
if ($result->num_rows > 0) {
    // Data exists, proceed to display
} else {
    $error = "No questions available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customer Questions</title>
    <link rel="stylesheet" href="view_feedback_style.css"> <!-- Link to your stylesheet for styling -->
</head>
<body>

    <div class="container">
        <h1>Customer Questions</h1>

        <!-- Display error message if no data exists -->
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Table to display the questions -->
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display each record from the database
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
