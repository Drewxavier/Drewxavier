<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mark complaint as resolved
    $conn->query("UPDATE contact_requests SET status = 'resolved' WHERE id = $id");

    header("Location: admin_dashboard.php");
    exit();
}
?>
