<?php
// Start session to keep track of logged in user
session_start();
// Include the file that connects to my database
require_once 'config/database.php';

// Variable to show messages to the user
$message = '';

// Check if someone submitted the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connect to my database
    $conn = connectDB();
    
    // Add the new plan to my database
    // Get all the information from the form and save it
    $sql = "INSERT INTO plans (user_id, title, category, duration, difficulty_level, description, plan_details) 
            VALUES ({$_SESSION['user_id']}, 
                    '{$_POST['title']}', 
                    '{$_POST['category']}', 
                    {$_POST['duration']}, 
                    '{$_POST['difficulty_level']}', 
                    '{$_POST['description']}',
                    '{$_POST['plan_details']}')";
    
    // If the plan was saved successfully, show a success message
    if ($conn->query($sql)) {
        $message = "Plan created successfully!";
    }
}

// Get all plans from database to show them on the page
$conn = connectDB();
$result = $conn->query("SELECT * FROM plans");
$plans = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Plans - FitTrack</title>
    <!-- CSS styles -->
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

        /* Success message style */
        .success {
            color: green;
        }

        /* Center the container */
        .container {
            max-width: 1200px;
            margin: 0 auto;    /* This centers the container */
        }

        
        button {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            width: 50%;            
            cursor: pointer;        /* Shows hand cursor on hover */
        }

        /* Keep form section on left */
        .form-section {
            float: left;
            width: 35%;
            padding: 10px;
        }

        /* Keep plans section on right */
        .plans-section {
            float: right;
            width: 60%;
            padding: 10px;
            margin-right: 20px;
        }

        input, select, textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        textarea {
            height: 100px;
        }

        .plan-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .view-btn {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 3px;
            margin-top: 10px;
        }

        .view-btn:hover {
            background: #45a049;
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

    <!-- Main content of the page -->
    <div class="container">
        <!-- Form to create new plans -->
        <div class="form-section">
            <h2>Create New Plan</h2>
            <!-- Show success message if plan was created -->
            <?php if ($message): ?>
                <p class="success"><?php echo $message; ?></p>
            <?php endif; ?>

            <!-- Form for creating a new plan -->
            <form method="POST">
                <!-- Plan title input -->
                <label>Plan Title:</label>
                <input type="text" name="title" required>

                <!-- Category dropdown -->
                <label>Category:</label>
                <select name="category" required>
                    <option value="Weight Loss">Weight Loss</option>
                    <option value="Muscle Gain">Muscle Gain</option>
                    <option value="Endurance">Endurance</option>
                    <option value="Flexibility">Flexibility</option>
                    <option value="General Fitness">General Fitness</option>
                </select>

                <!-- Duration input -->
                <label>Duration (days):</label>
                <input type="number" name="duration" min="1" required>

                <!-- Difficulty level dropdown -->
                <label>Difficulty Level:</label>
                <select name="difficulty_level" required>
                    <option value="beginner">Beginner</option>
                    <option value="medium">Medium</option>
                    <option value="intermediate">Intermediate</option>
                </select>

                <!-- Description textarea -->
                <label>Description:</label>
                <textarea name="description" required></textarea>

                <!-- Plan details textarea -->
                <label>Plan Details (Day by Day):</label>
                <textarea name="plan_details" placeholder="Day 1: ...&#10;Day 2: ...&#10;Day 3: ..." required></textarea>

                <!-- Submit button -->
                <button type="submit">Create Plan</button>
            </form>
        </div>

        <!-- Section to display all plans -->
        <div class="plans-section">
            <h2>All Plans</h2>
            <!-- Check if there are any plans -->
            <?php if (empty($plans)): ?>
                <p>No plans created yet.</p>
            <?php else: ?>
                <!-- Loop through all plans and show them -->
                <?php foreach ($plans as $plan): ?>
                    <div class="plan-box">
                        <h3><?php echo $plan['title']; ?></h3>
                        <p>Category: <?php echo $plan['category']; ?></p>
                        <p>Duration: <?php echo $plan['duration']; ?> days</p>
                        <p>Difficulty: <?php echo $plan['difficulty_level']; ?></p>
                        <a href="view_plan.php?plan_id=<?php echo $plan['plan_id']; ?>">View Plan</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 