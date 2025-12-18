<?php
// Database configuration
$host = 'localhost';
$user = 'root'; // Default XAMPP user
$pass = '';     // Default XAMPP password
$db   = 'gamo_dental';

$port = 4306;

// Create connection
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
