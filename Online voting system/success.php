<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Submitted</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: url('ballot3.jpg') no-repeat center center/cover;
            color: white;
            padding: 50px;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
        .success-message {
            font-size: 24px;
            font-weight: bold;
            color: lightgreen;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 18px;
            color: yellow;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="success-message">✅ Your vote has been successfully submitted!</p>
        <a href="dashboard.php" class="back-link">🏠 Return to Dashboard</a>
    </div>
</body>
</html>
