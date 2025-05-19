<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "Drawing4000";  // Your MySQL password
$dbname = "user_login"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define users with hashed passwords
$users = [
    ["C027-01-0563/2023", password_hash("Andrew#01", PASSWORD_DEFAULT)],
    ["C027-01-0866/2023", password_hash("Brenda#02", PASSWORD_DEFAULT)],
    ["C027-01-0797/2023", password_hash("Grace#03", PASSWORD_DEFAULT)],
    ["C027-01-0818/2023", password_hash("Timothy#04", PASSWORD_DEFAULT)]
];

// Insert users into the database
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

foreach ($users as $user) {
    $stmt->bind_param("ss", $user[0], $user[1]);
    $stmt->execute();
}

echo "Users inserted with hashed passwords successfully.";

$stmt->close();
$conn->close();
?>
