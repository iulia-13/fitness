<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id'];

// Simple form handling for new goal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['title']) && isset($_POST['category']) && isset($_POST['description']) && isset($_POST['target_date'])) {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $target_date = $_POST['target_date'];
        
        $sql = "INSERT INTO goals (user_id, title, category, description, target_date) 
                VALUES ('$user_id', '$title', '$category', '$description', '$target_date')";
        
        $conn->query($sql);
    }
    
    // complete goal (simpler version)
    if (isset($_POST['complete_goal'])) {
        $goal_id = $_POST['goal_id'];
        $sql = "UPDATE goals SET is_completed = 1 WHERE goal_id = $goal_id";
        $conn->query($sql);
        // Refresh the page to show changes
        header("Location: goals.php");
        exit();
    }

    //  delete goal 
    if (isset($_POST['delete_goal'])) {
        $goal_id = $_POST['goal_id'];
        $sql = "DELETE FROM goals WHERE goal_id = $goal_id";
        $conn->query($sql);
    }
}

// Get goals
$sql = "SELECT * FROM goals WHERE user_id = '$user_id' ORDER BY target_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Goals - FitTrack</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 20px;
            background-color: #f0f5ff;
        }

        .navbar {
            background-color: #333;
            padding: 15px 0;
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .nav-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .nav-links a {
            color: white;
            padding: 5px 10px;
            margin-left: 20px;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-section {
            float: left;
            width: 35%;
            padding: 10px;
        }

        .goals-section {
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

        button {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            width: 50%;
            cursor: pointer;
        }

        .goal-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background: white;
        }

        .completed {
            background:rgb(181, 240, 181);
        }

        .button-group {
            margin-top: 10px;
        }

        .btn-complete {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-right: 5px;
            width: auto;
        }

        .btn-delete {
            background: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            width: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="nav-logo">FitTrack</div>
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
        <div class="form-section">
            <h2>Create New Goal</h2>
            <form method="POST">
                <div>
                    <label>Title:</label>
                    <input type="text" name="title" required>
                </div>

                <div>
                    <label>Category:</label>
                    <select name="category" required>
                        <option value="Weight Loss">Weight Loss</option>
                        <option value="Muscle Gain">Muscle Gain</option>
                        <option value="Endurance">Endurance</option>
                        <option value="Flexibility">Flexibility</option>
                    </select>
                </div>

                <div>
                    <label>Description:</label>
                    <textarea name="description" required></textarea>
                </div>

                <div>
                    <label>Target Date:</label>
                    <input type="date" name="target_date" required>
                </div>

                <button type="submit">Add Goal</button>
            </form>
        </div>

        <div class="goals-section">
            <h2>Your Goals</h2>
            <?php while ($goal = $result->fetch_assoc()): ?>
                <div class="goal-box <?php echo ($goal['is_completed'] == 1) ? 'completed' : ''; ?>">
                    <h3><?php echo $goal['title']; ?></h3>
                    <p>Category: <?php echo $goal['category']; ?></p>
                    <p>Description: <?php echo $goal['description']; ?></p>
                    <p>Target Date: <?php echo $goal['target_date']; ?></p>
                    
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="goal_id" value="<?php echo $goal['goal_id']; ?>">
                        <button type="submit" name="complete_goal" class="btn-complete">✓</button>
                        <button type="submit" name="delete_goal" class="btn-delete">✗</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html> 