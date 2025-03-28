<?php
// Initialize variables for BMI result and error message
$bmi = "";
$errorMessage = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    // Validate input
    if (empty($weight) || empty($height)) {
        $errorMessage = "Please enter both weight and height.";
    } else {
        // Calculate BMI (weight in kg, height in meters)
        $bmi = $weight / ($height * $height);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <link rel="stylesheet" href="bmi_calculator_style.css">
</head>
<body>
    <div class="container">
        <h1>BMI Calculator</h1>

        <!-- Error message -->
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- BMI Result -->
        <?php if ($bmi): ?>
            <div class="result">
                <p>Your BMI: <?php echo number_format($bmi, 2); ?></p>
                <p>
                    <?php
                    // Classify BMI result
                    if ($bmi < 18.5) {
                        echo "Underweight";
                    } elseif ($bmi >= 18.5 && $bmi < 24.9) {
                        echo "Normal weight";
                    } elseif ($bmi >= 25 && $bmi < 29.9) {
                        echo "Overweight";
                    } else {
                        echo "Obesity";
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- BMI Calculator Form -->
        <form action="bmi_calculator.php" method="POST">
            <div class="form-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" name="weight" id="weight" value="<?php echo isset($weight) ? $weight : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="height">Height (m):</label>
                <input type="number" name="height" id="height" value="<?php echo isset($height) ? $height : ''; ?>" step="0.01" required>
            </div>
            <button type="submit">Calculate BMI</button>
        </form>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

