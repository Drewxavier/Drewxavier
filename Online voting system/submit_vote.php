<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ✅ Database Connection
$servername = "localhost";
$db_username = "root";
$db_password = "Drawing4000";
$dbname = "user_login";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// ✅ Check if the user has already voted
$query = "SELECT * FROM votes WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$existingVote = $result->fetch_assoc();
$stmt->close();

if ($existingVote) {
    echo "<script>
            alert('You have already voted!');
            window.location.href='review_vote.php';
          </script>";
    exit();
}

// ✅ Get candidate names from form
$president = $_POST['president'] ?? '';
$vice_president = $_POST['vice_president'] ?? '';
$secretary = $_POST['secretary'] ?? '';
$treasurer = $_POST['treasurer'] ?? '';

// ✅ Ensure all positions are voted for
if (empty($president) || empty($vice_president) || empty($secretary) || empty($treasurer)) {
    echo "<script>
            alert('You must vote for one candidate in each position!');
            window.location.href='dashboard.php';
          </script>";
    exit();
}

// ✅ Insert vote using candidate names
$query = "INSERT INTO votes (username, president, vice_president, secretary, treasurer, submitted) 
          VALUES (?, ?, ?, ?, ?, TRUE)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $username, $president, $vice_president, $secretary, $treasurer);

if ($stmt->execute()) {
    // ✅ Mark user as voted
    $updateVoteStatus = "UPDATE users SET has_voted = 1 WHERE username = ?";
    $stmt = $conn->prepare($updateVoteStatus);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    header("Location: success.php");
    exit();
} else {
    echo "<script>
            alert('Error submitting your vote. Please try again.');
            window.location.href='dashboard.php';
          </script>";
}

// ✅ Close connection
$stmt->close();
$conn->close();
?>
