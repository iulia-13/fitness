<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    if (isset($_POST['add_goal'])) {
        $sql = "INSERT INTO goals (user_id, title, category, description, target_date) 
                VALUES ({$_SESSION['user_id']}, 
                        '{$_POST['title']}', 
                        '{$_POST['category']}', 
                        '{$_POST['description']}', 
                        '{$_POST['target_date']}')";
        
        if ($conn->query($sql)) {
            $message = "Goal added successfully!";
        }
    } 
    elseif (isset($_POST['complete_goal'])) {
        $goal_id = $_POST['goal_id'];
        $conn->query("UPDATE goals SET is_completed = 1 WHERE goal_id = $goal_id");
    }
    elseif (isset($_POST['delete_goal'])) {
        $goal_id = $_POST['goal_id'];
        $conn->query("DELETE FROM goals WHERE goal_id = $goal_id");
    }
}

// Get goals
$conn = connectDB();
$result = $conn->query("SELECT * FROM goals WHERE user_id = {$_SESSION['user_id']} ORDER BY target_date ASC");
$goals = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Goals - FitTrack</title>
    <style>
        body {
            background-color: lightblue;
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }

        .navbar {
            background-color: white;
            padding: 15px 0;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            color: #333;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            padding: 5px 10px;
        }

        .nav-links a:hover {
            background-color: #f0f0f0;
            border-radius: 4px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
        }

        .form-section {
            flex: 35%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .goals-section {
            flex: 60%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        textarea {
            height: 100px;
        }

        button {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
        }

        .goal-box {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
            border: none;
        }

        .completed {
            opacity: 0.7;
            background: #f9f9f9;
        }

        .completed h3 {
            text-decoration: line-through;
        }

        .goal-actions {
            margin-top: 10px;
        }

        .goal-actions button {
            width: auto;
            padding: 5px 10px;
            margin-right: 5px;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
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

    <div class="container">
        <div class="form-section">
            <h2>Add New Goal</h2>
            <?php if ($message): ?>
                <p class="success"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Goal Title:</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Category:</label>
                    <select name="category" required>
                        <option value="Weight Loss">Weight Loss</option>
                        <option value="Muscle Gain">Muscle Gain</option>
                        <option value="Endurance">Endurance</option>
                        <option value="Flexibility">Flexibility</option>
                        <option value="General Fitness">General Fitness</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label>Target Date:</label>
                    <input type="date" name="target_date" required>
                </div>

                <button type="submit" name="add_goal">Add Goal</button>
            </form>
        </div>

        <div class="goals-section">
            <h2>Your Goals</h2>
            <?php if (empty($goals)): ?>
                <p>No goals added yet.</p>
            <?php else: ?>
                <?php foreach ($goals as $goal): ?>
                    <div class="goal-box <?php echo $goal['is_completed'] ? 'completed' : ''; ?>">
                        <h3><?php echo $goal['title']; ?></h3>
                        <p><strong>Category:</strong> <?php echo $goal['category']; ?></p>
                        <p><strong>Description:</strong><br><?php echo $goal['description']; ?></p>
                        <p><strong>Target Date:</strong> <?php echo date('M d, Y', strtotime($goal['target_date'])); ?></p>
                        
                        <?php if (!$goal['is_completed']): ?>
                            <div class="goal-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="goal_id" value="<?php echo $goal['goal_id']; ?>">
                                    <button type="submit" name="complete_goal">Complete</button>
                                    <button type="submit" name="delete_goal" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 