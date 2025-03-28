<?php
session_start();
include("dbconfig.php");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Query to get all meal plans from the meal_plans table
$query = "SELECT * FROM meal_plans";
$result = $conn->query($query);

// Check if there are any meal plans in the table
if ($result->num_rows > 0) {
    $mealPlans = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $mealPlans = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Plans</title>
    <link rel="stylesheet" href="view_meal_plan_style.css"> <!-- Your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Our Meal Plans</h1>

        <?php if (empty($mealPlans)): ?>
            <p>No meal plans available at the moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Meal Type</th>
                        <th>Muscle Gain</th>
                        <th>Fat Loss</th>
                        <th>General Fitness</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mealPlans as $mealPlan): ?>
                        <tr>
                            <td><?php echo $mealPlan['meal_type']; ?></td>
                            <td><?php echo $mealPlan['muscle_gain']; ?></td>
                            <td><?php echo $mealPlan['fat_loss']; ?></td>
                            <td><?php echo $mealPlan['general_fitness']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
