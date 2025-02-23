<?php
require_once 'config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    // Get and sanitize form data
    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $weight = filter_var($_POST['weight'], FILTER_VALIDATE_FLOAT);
    $height = filter_var($_POST['height'], FILTER_VALIDATE_FLOAT);
    $difficulty = $_POST['difficulty'];
    
    // Basic validation
    if ($age < 16 || $age > 100) {
        $message = "Age must be between 16 and 100";
        $messageType = 'error';
    } else {
        // Update user profile
        $stmt = $conn->prepare("UPDATE users SET name = ?, age = ?, weight = ?, height = ?, difficulty_level = ?, profile_completed = 1 WHERE user_id = ?");
        $stmt->bind_param("sidisi", $name, $age, $weight, $height, $difficulty, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $_SESSION['profile_completed'] = true;
            header('Location: dashboard.php');
            exit();
        } else {
            $message = "Error updating profile";
            $messageType = 'error';
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Your Profile - FitTrack</title>
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Your Profile</h2>
        <?php if ($message): ?>
            <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Age:</label>
                <input type="number" name="age" required>
            </div>
            <div class="form-group">
                <label>Weight (kg):</label>
                <input type="number" step="0.1" name="weight" required>
            </div>
            <div class="form-group">
                <label>Height (cm):</label>
                <input type="number" step="0.1" name="height" required>
            </div>
            <div class="form-group">
                <label>Fitness Level:</label>
                <select name="difficulty" required>
                    <option value="beginner">Beginner</option>
                    <option value="medium">Medium</option>
                    <option value="intermediate">Intermediate</option>
                </select>
            </div>
            <button type="submit">Complete Profile</button>
        </form>
    </div>
</body>
</html> 