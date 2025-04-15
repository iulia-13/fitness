<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = 'Email already exists';
    } else {
        // Create new account
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        if ($conn->query($sql)) {
            $_SESSION['user_id'] = $conn->insert_id;
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Error creating account';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - FitTrack</title>
    <style>
        body {
            background-color: #ADD8E6;
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Create Account</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</body>
</html> 