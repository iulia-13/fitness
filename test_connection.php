<?php
require_once 'config/database.php';

try {
    $conn = connectDB();
    echo "Database connection successful!<br>";
    
    // Test query
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    $data = $result->fetch_assoc();
    echo "Number of users in database: " . $data['total'];
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} 