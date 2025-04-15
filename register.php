<?php
require_once 'config/database.php';

// Initialize variables
$message = '';
$messageType = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    // Get and sanitize form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
        $messageType = 'error';
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long";
        $messageType = 'error';
    } else {
        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();
        
        if ($result->num_rows > 0) {
            $message = "Email already registered";
            $messageType = 'error';
        } else {
            // Prepare and execute the insert statement with minimal fields
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $email, $hashedPassword);
            
            if ($stmt->execute()) {
                // Get the newly created user_id
                $userId = $conn->insert_id;
                
                // Start the session and set session variables
                session_start();  // Make sure session is started
                $_SESSION['user_id'] = $userId;
                
                // Changed redirect to profile_setup.php
                header('Location: profile_setup.php');
                exit();
            } else {
                $message = "Error: Registration failed";
                $messageType = 'error';
            }
            $stmt->close();
        }
        $checkEmail->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - FitTrack</title>
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
        <h2>Create Account</h2>
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
            <button type="submit">Create Account</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</body>
</html> 