<?php
require_once 'config/database.php';
session_start();

// Initialize variables
$message = '';
$messageType = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    // Get and sanitize form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
        $messageType = 'error';
    } else {
        // Prepare and execute the select statement
        $stmt = $conn->prepare("SELECT user_id, password, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login successful - Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                
                // Check if profile is completed
                $profileCheck = $conn->prepare("SELECT profile_completed FROM users WHERE user_id = ?");
                $profileCheck->bind_param("i", $user['user_id']);
                $profileCheck->execute();
                $profileResult = $profileCheck->get_result();
                $profileData = $profileResult->fetch_assoc();
                
                header('Location: dashboard.php');
                exit();
            } else {
                $message = "Invalid password";
                $messageType = 'error';
            }
        } else {
            $message = "Email not found";
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
    <title>Login - FitTrack</title>
    <style>
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .error { color: red; }
        .success { color: green; }
        .form-group { 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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
        .logo {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">FitTrack</div>
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html> 