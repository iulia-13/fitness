<?php
// Database settings
$db_host = 'localhost';     // (1)
$db_user = 'fitnessdb';     // (2)
$db_pass = 'fitnessdb';     // (3)
$db_name = 'fitnessdb';     // (4)

// Function to connect to database
function connectDB() {
    // Use global variables inside function
    global $db_host, $db_user, $db_pass, $db_name;
    
    // Try to connect
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Check if connection worked
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    
    // Set the character encoding
    $conn->set_charset("utf8mb4");
    
    return $conn;
} 