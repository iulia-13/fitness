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
    if (isset($_POST['add_goal'])) {
        // Add new goal
        $title = trim($_POST['title']);
        $category = $_POST['category'];
        $description = trim($_POST['description']);
        $target_date = $_POST['target_date'];
        
        $conn = connectDB();
        $stmt = $conn->prepare("INSERT INTO goals (user_id, title, category, description, target_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $_SESSION['user_id'], $title, $category, $description, $target_date);
        
        if ($stmt->execute()) {
            $message = "Goal added successfully!";
            $messageType = 'success';
        } else {
            $message = "Error adding goal";
            $messageType = 'error';
        }
        $stmt->close();
        $conn->close();
    } elseif (isset($_POST['complete_goal'])) {
        // Mark goal as completed
        $goal_id = $_POST['goal_id'];
        
        $conn = connectDB();
        $stmt = $conn->prepare("UPDATE goals SET is_completed = 1 WHERE goal_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $goal_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    } elseif (isset($_POST['delete_goal'])) {
        $goal_id = $_POST['goal_id'];
        
        $conn = connectDB();
        $stmt = $conn->prepare("DELETE FROM goals WHERE goal_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $goal_id, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $message = "Goal deleted successfully!";
            $messageType = 'success';
            // Refresh the page to show updated list
            header('Location: goals.php');
            exit();
        } else {
            $message = "Error deleting goal";
            $messageType = 'error';
        }
        $stmt->close();
        $conn->close();
    }
}

// Fetch user's goals
$conn = connectDB();
$stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY target_date ASC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$goals = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Goals - FitTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Navigation Bar (same as dashboard) */
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
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        /* Form Styles */
        .goal-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Goals List Styles */
        .goals-list {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
        }

        .goal-item {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: block;
        }

        .goal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .goal-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }

        .goal-category {
            display: inline-block;
            padding: 3px 8px;
            background: #4CAF50;
            color: white;
            border-radius: 12px;
            font-size: 0.8em;
            margin-bottom: 10px;
        }

        .goal-description {
            color: #666;
            margin: 10px 0;
        }

        .goal-date {
            color: #888;
            font-size: 0.9em;
        }

        .goal-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .goal-actions button {
            width: auto;
            padding: 5px 15px;
        }

        .edit-btn {
            background-color: #2196F3;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .complete-btn {
            background-color: #4CAF50;
        }

        .completed {
            opacity: 0.7;
        }

        .completed .goal-title {
            text-decoration: line-through;
        }

        .success { color: green; }
        .error { color: red; }
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

    <div class="container">
        <!-- Goal Form -->
        <div class="goal-form">
            <h2>Add New Goal</h2>
            <?php if ($message): ?>
                <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
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
                    <label>Goal Description:</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Target Date:</label>
                    <input type="date" name="target_date" required>
                </div>
                <button type="submit" name="add_goal">Add Goal</button>
            </form>
        </div>

        <!-- Goals List -->
        <div class="goals-list">
            <h2>Your Goals</h2>
            <?php if (empty($goals)): ?>
                <p>No goals added yet.</p>
            <?php else: ?>
                <?php foreach ($goals as $goal): ?>
                    <div class="goal-item <?php echo $goal['is_completed'] ? 'completed' : ''; ?>">
                        <div class="goal-header">
                            <div class="goal-title"><?php echo htmlspecialchars($goal['title']); ?></div>
                            <div class="goal-category"><?php echo htmlspecialchars($goal['category']); ?></div>
                        </div>
                        <div class="goal-description"><?php echo htmlspecialchars($goal['description']); ?></div>
                        <div class="goal-date">Target: <?php echo date('M d, Y', strtotime($goal['target_date'])); ?></div>
                        
                        <div class="goal-actions">
                            <?php if (!$goal['is_completed']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="goal_id" value="<?php echo $goal['goal_id']; ?>">
                                    <button type="submit" name="complete_goal" class="complete-btn">Complete</button>
                                </form>
                            <?php endif; ?>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this goal?');">
                                <input type="hidden" name="goal_id" value="<?php echo $goal['goal_id']; ?>">
                                <button type="submit" name="delete_goal" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 