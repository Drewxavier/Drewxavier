<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "Drawing4000"; // Your MySQL password
$dbname = "user_login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Check if user has already voted
$stmt = $conn->prepare("SELECT * FROM votes WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You have already voted!";
    exit();
}

$stmt->close();

// Get user input
$president = $_POST['president'];
$vice_president = $_POST['vice_president'];
$secretary = $_POST['secretary'];
$treasurer = $_POST['treasurer'];

// Insert the vote into the database
$stmt = $conn->prepare("INSERT INTO votes (username, president, vice_president, secretary, treasurer) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $president, $vice_president, $secretary, $treasurer);

if ($stmt->execute()) {
    echo "Vote submitted successfully!";
    header("Location: dashboard.php");
} else {
    echo "Error submitting vote: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
