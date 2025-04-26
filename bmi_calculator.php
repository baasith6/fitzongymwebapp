<?php
session_start();
include("dbconfig.php");

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$bmi = "";
$category = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    if (empty($weight) || empty($height) || $weight <= 0 || $height <= 0) {
        $errorMessage = "Please enter valid positive numbers for weight and height.";
    } else {
        $bmi = round($weight / ($height * $height), 2);

        if ($bmi < 18.5) {
            $category = "Underweight";
        } elseif ($bmi < 24.9) {
            $category = "Normal weight";
        } elseif ($bmi < 29.9) {
            $category = "Overweight";
        } else {
            $category = "Obesity";
        }

        // Save the record (optional)
        $stmt = $conn->prepare("INSERT INTO bmi_records (email, weight, height, bmi, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sddds", $email, $weight, $height, $bmi, $category);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BMI Calculator</title>
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
            margin-right: 10px;
            cursor: pointer;
            color: var(--primary-color);
        }

        .result-box {
            background-color: #f0f0f0;
            padding: 15px;
            margin-top: 20px;
            border-radius: 6px;
            font-size: 1.1rem;
            color: #333;
        }

        .category {
            font-weight: bold;
            margin-top: 10px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <i class='bx bx-arrow-back back-arrow' onclick="window.location.href='customer_dashboard.php'"></i>
        <h1>BMI Calculator</h1>
    </div>

    <?php if ($errorMessage): ?>
        <div class="error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form id="bmiForm" action="bmi_calculator.php" method="POST">
        <div class="form-group">
            <label for="weight">Weight (kg):</label>
            <input type="number" name="weight" id="weight" value="<?php echo isset($weight) ? $weight : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="height">Height (m):</label>
            <input type="number" step="0.01" name="height" id="height" value="<?php echo isset($height) ? $height : ''; ?>" required>
        </div>
        <button type="submit">Calculate BMI</button>
    </form>

    <?php if ($bmi): ?>
        <div class="result-box">
            <p>Your BMI is <strong><?php echo $bmi; ?></strong></p>
            <p class="category">
                Status: 
                <?php
                    if ($category === "Underweight") echo "<span style='color: #3498db;'>$category</span>";
                    elseif ($category === "Normal weight") echo "<span style='color: #27ae60;'>$category</span>";
                    elseif ($category === "Overweight") echo "<span style='color: #f39c12;'>$category</span>";
                    else echo "<span style='color: #e74c3c;'>$category</span>";
                ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('bmiForm').addEventListener('submit', function(e) {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);

    if (isNaN(weight) || isNaN(height) || weight <= 0 || height <= 0) {
        alert("Please enter valid positive numbers for weight and height.");
        e.preventDefault();
    }
});
</script>
</body>
</html>
