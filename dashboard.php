<?php
// Start the session so I can use $_SESSION
session_start();
// Include the database connection file I made
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id'];

//  SQL query to use user_id
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($result && $result->num_rows > 0) {
    $name = $user['name']; // 
} else {
    $name = "User";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - FitTrack</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 20px;
            background-color: #f0f5ff;
        }

        /* Style the top navigation bar */
        .navbar {
            background-color: #333;
            padding: 15px 0;
        }

        /* Center the navigation content */
        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        /* Style the logo */
        .nav-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        /* Style the navigation links */
        .nav-links a {
            color: white;
            padding: 5px 10px;
            margin-left: 20px;
            text-decoration: none;
        }

        /* Center the container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Left section styling */
        .left-box {
            float: left;
            width: 35%;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }

        /* Right section styling */
        .right-box {
            float: right;
            width: 60%;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }

        h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            line-height: 1.5;
        }

        .profile-details {
            padding: 10px;
        }

        .profile-details p {
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-details strong {
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Navigation bar at the top -->
    <nav class="navbar">
        <div class="nav-content">
            <a href="dashboard.php" class="nav-logo">FitTrack</a>
            <!-- Links to other pages -->
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
        <div class="left-box">
            <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
            <p>Welcome to your fitness dashboard.</p>
        </div>

        <div class="right-box">
            <h2>Your Profile</h2>
            <div class="profile-details">
                <p><strong>Weight:</strong> <?php echo htmlspecialchars($user['weight']); ?> kg</p>
                <p><strong>Height:</strong> <?php echo htmlspecialchars($user['height']); ?> cm</p>
                <p><strong>Difficulty Level:</strong> <?php echo htmlspecialchars($user['difficulty_level']); ?></p>
            </div>
        </div>
    </div>
</body>
</html> 