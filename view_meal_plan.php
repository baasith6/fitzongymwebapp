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
    <link rel="stylesheet" href="datatables_admin_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .top-bar {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-arrow {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 10px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .back-arrow:hover {
            color: var(--primary-hover);
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .profile-info label {
            font-weight: bold;
            margin-right: 10px;
        }

        .profile-info p {
            margin-bottom: 12px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <i class='bx bx-arrow-back back-arrow' onclick="window.location.href='customer_dashboard.php'"></i>
            <h1>Meal Plans</h1>
        </div>
        

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

        
    </div>
</body>
</html>
