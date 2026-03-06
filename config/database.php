<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'trader_system';

try {
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        // Show login page even without database for demo
        error_log("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}
?>
