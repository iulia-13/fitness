<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = connectDB();

// Get plan details if plan_id is provided
if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    $sql = "SELECT * FROM plans WHERE plan_id = $plan_id";
    $result = $conn->query($sql);
    $plan = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Plan - FitTrack</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f0f5ff;
            padding: 20px;
        }
        .plan-details {
            background: white;
            padding: 20px;
            margin: 20px auto;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="plan-details">
        <a href="plans.php">Back to Plans</a>
        
        <?php if (isset($plan)): ?>
            <h2><?php echo $plan['title']; ?></h2>
            <p><strong>Category:</strong> <?php echo $plan['category']; ?></p>
            <p><strong>Duration:</strong> <?php echo $plan['duration']; ?> days</p>
            <p><strong>Difficulty:</strong> <?php echo $plan['difficulty_level']; ?></p>
            
            <h3>Description:</h3>
            <p><?php echo nl2br($plan['description']); ?></p>
            
            <h3>Plan details:</h3>
            <p><?php echo nl2br($plan['plan_details']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html> 