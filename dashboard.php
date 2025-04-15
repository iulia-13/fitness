<?php
// Start the session so we can use $_SESSION
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

// Fixed SQL query to use user_id
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['name']; // 
} else {
    $name = "User";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - FitTrack</title>
    <!-- My CSS styles -->
    <style>
        /* Basic page styling */
        body {
            background-color: #ADD8E6;
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }

        /* Navigation bar styling */
        .navbar {
            background-color: #333; /* Dark background for navbar */
            padding: 15px 0;
            margin-bottom: 30px;
        }

        /* Center the navbar content */
        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        /* Style for my logo */
        .nav-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        /* Navigation links container */
        .nav-links {
            display: flex;
            gap: 20px; /* Space between links */
        }

        /* Style the navigation links */
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
        }

        /* Hover effect for links */
        .nav-links a:hover {
            background-color: #555;
            border-radius: 4px;
        }

        /* Main content container */
        .container {
            background: white;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Style for the welcome message box */
        .welcome-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }

        /* Make the dashboard boxes side by side */
        .dashboard-grid {
            display: flex;
            gap: 20px;
        }

        /* Style for each dashboard box */
        .dashboard-box {
            border: 1px solid #ddd;
            padding: 15px;
            width: 50%; /* Make boxes equal width */
        }

        /* Style the lists in the boxes */
        .plan-list {
            list-style: none;
            padding: 0;
        }

        /* Style each list item */
        .plan-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        /* Remove border from last item */
        .plan-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar">
        <div class="nav-content">
            <!-- My website logo -->
            <a href="dashboard.php" class="nav-logo">FitTrack</a>
            <!-- Navigation links -->
            <div class="nav-links">
                <a href="dashboard.php">Home</a>
                <a href="plans.php">Plans</a>
                <a href="goals.php">Goals</a>
                <a href="calendar.php">Calendar</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container">
        <!-- Welcome message box -->
        <div class="welcome-box">
            <!-- Show the user's name from the database -->
            <h1>Welcome back, <?php echo htmlspecialchars($name); ?>!</h1>
            <p>Track your fitness journey and achieve your goals with FitTrack.</p>
        </div>

        <!-- Dashboard grid with two boxes -->
        <div class="dashboard-grid">
            <!-- Recent plans box -->
            <div class="dashboard-box">
                <h2>Recently Accessed Plans</h2>
                <ul class="plan-list">
                    <li>No plans accessed yet</li>
                </ul>
            </div>

            <!-- Recommended plans box -->
            <div class="dashboard-box">
                <h2>Recommended for You</h2>
                <ul class="plan-list">
                    <li>Beginner Full Body Workout</li>
                    <li>30-Day Fitness Challenge</li>
                    <li>Basic Cardio Plan</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 