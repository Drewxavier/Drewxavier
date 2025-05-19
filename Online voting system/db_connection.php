<?php
$servername = "localhost"; // Change this if using a different server
$username = "root"; // Your database username (default for local development)
$password = "Drawing4000"; // Your database password (leave blank if using default settings)
$database = "user_login"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
