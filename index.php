<?php
// Start session to manage user login state
session_start();

// If user is logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FitTrack - Your Fitness Journey</title>
    <style>
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 200px;
        }
        .logo {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">FitTrack</div>
        <a href="register.php" class="btn">Create Account</a>
        <br>
        <a href="login.php" class="btn">Login</a>
    </div>
</body>
</html> 