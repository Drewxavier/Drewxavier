<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "Drawing4000";
$dbname = "user_login";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// ✅ Fetch the logged-in user's votes
$query = "SELECT president, vice_president, secretary, treasurer FROM votes WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("You have not voted yet! <a href='dashboard.php'>Go Back</a>");
}

$vote = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Vote</title>
    <style>
        body {
            background: url('ballot3.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
            padding: 20px;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
        }
        h2 {
            color: yellow;
        }
        .vote-review {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .back-btn {
            padding: 10px 20px;
            background-color: green;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Review Your Vote</h2>
        <p class="vote-review">🗳️ <strong>President:</strong> <?php echo htmlspecialchars($vote['president']); ?></p>
        <p class="vote-review">🗳️ <strong>Vice President:</strong> <?php echo htmlspecialchars($vote['vice_president']); ?></p>
        <p class="vote-review">🗳️ <strong>Secretary:</strong> <?php echo htmlspecialchars($vote['secretary']); ?></p>
        <p class="vote-review">🗳️ <strong>Treasurer:</strong> <?php echo htmlspecialchars($vote['treasurer']); ?></p>

        <a href="dashboard.php" class="back-btn">Go Back</a>
    </div>
</body>
</html>
