<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar - FitTrack</title>
    <style>
        /* Basic page styling */
        body {
            font-family: Arial;
            margin: 0;
            padding: 20px;
            background-color: #f0f5ff;  
        }

        /* Navigation bar styling */
        .navbar {
            background-color: #333;
            padding: 15px 0;
        }

        /* Navigation content container */
        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        /* Logo styling */
        .nav-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        /* Navigation links */
        .nav-links a {
            color: white;
            padding: 5px 10px;
            margin-left: 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-content">
            <a href="dashboard.php" class="nav-logo">FitTrack</a>
            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="dashboard.php">Home</a>
                <a href="plans.php">Plans</a>
                <a href="goals.php">Goals</a>
                <a href="calendar.php">Calendar</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Fitness Calendar</h2>
    </div>
</body>
</html> 