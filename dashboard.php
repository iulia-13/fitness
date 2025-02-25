<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user name from database if not in session
if (!isset($_SESSION['user_name'])) {
    require_once 'config/database.php';
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $_SESSION['user_name'] = $user['name'];
    $stmt->close();
    $conn->close();
}

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - FitTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #333;
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .nav-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
        }

        .nav-links a:hover {
            background-color: #555;
            border-radius: 4px;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .welcome-section {
            background-color: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .welcome-section h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-message {
            color: #666;
            font-size: 16px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dashboard-card h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .plan-list {
            list-style: none;
        }

        .plan-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .plan-list li:last-child {
            border-bottom: none;
        }

        .logout-btn {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-content">
            <a href="dashboard.php" class="nav-logo">FitTrack</a>
            <div class="nav-links">
                <a href="dashboard.php">Home</a>
                <a href="plans.php">Plans</a>
                <a href="goals.php">Goals</a>
                <a href="calendar.php">Calendar</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($userName); ?>!</h1>
            <p class="welcome-message">Track your fitness journey and achieve your goals with FitTrack.</p>
        </div>

        <div class="dashboard-grid">
            <!-- Recent Plans -->
            <div class="dashboard-card">
                <h2>Recently Accessed Plans</h2>
                <ul class="plan-list">
                    <li>No plans accessed yet</li>
                    <!-- We'll populate this dynamically later -->
                </ul>
            </div>

            <!-- Recommended Plans -->
            <div class="dashboard-card">
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