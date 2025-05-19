<?php
session_start();
include 'db_connection.php'; // Ensure database connection is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is an admin
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) { 
            $_SESSION['user_id'] = $admin['id']; // Store admin ID
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
            exit();
        }
    }

    // Check if user is in 'users' table
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) { 
            $_SESSION['user_id'] = $user['id']; // Store user ID
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'user';

            // Redirect directly to user dashboard (No OTP or phone check)
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
            exit();
        }
    }

    echo "<script>alert('Invalid username or password!'); window.location.href='login.php';</script>";
}
?>
