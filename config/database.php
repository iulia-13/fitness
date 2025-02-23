<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'fitnessdb');
define('DB_PASS', 'fitnessdb');
define('DB_NAME', 'fitnessdb');

/**
 * Creates and returns a database connection
 * @return mysqli Database connection object
 * @throws Exception if connection fails
 */
function connectDB() {
    try {
        // Disable error reporting for mysqli
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        // Create connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Set charset to ensure proper encoding
        $conn->set_charset("utf8mb4");
        
        return $conn;
    } catch (Exception $e) {
        // Log error (in a production environment, you'd want to log this properly)
        error_log("Database connection failed: " . $e->getMessage());
        
        // Show user-friendly message
        die("Sorry, there was a problem connecting to the database. Please try again later.");
    }
} 