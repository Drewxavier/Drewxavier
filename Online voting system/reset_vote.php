<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];

    // 🔍 Fetch username from users table
    $checkUserQuery = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        header("Location: admin_dashboard.php?error=User not found in the database!");
        exit();
    }

    $username = $user['username']; // Store username

    // ✅ Reset has_voted status
    $resetQuery = "UPDATE users SET has_voted = 0 WHERE id = ?";
    $stmt = $conn->prepare($resetQuery);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        // ✅ Delete votes using `username`
        $deleteVotesQuery = "DELETE FROM votes WHERE username = ?";
        $stmt = $conn->prepare($deleteVotesQuery);
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=Vote Reset Successfully!");
        } else {
            header("Location: admin_dashboard.php?error=Error deleting votes!");
        }
    } else {
        header("Location: admin_dashboard.php?error=Error resetting vote status!");
    }
    
    exit();
}
?>
