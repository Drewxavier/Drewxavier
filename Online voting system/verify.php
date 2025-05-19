<?php
session_start();
include 'db_connection.php';

// Redirect if session data is missing
if (!isset($_SESSION['user_id']) || !isset($_SESSION['phone_number'])) {
    header("Location: login.php");
    exit();
}

// Set the correct timezone
date_default_timezone_set('Africa/Nairobi');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $phone_number = $_SESSION['phone_number'];
    $otp = trim($_POST['otp']); // Trim spaces

    if (!preg_match('/^\d{6}$/', $otp)) { 
        $message = "Invalid OTP format!";
    } else {
        // Retrieve OTP details for debugging
        $stmt = $conn->prepare("SELECT otp_code, phone_number, expires_at FROM otp_codes WHERE user_id = ? ORDER BY expires_at DESC LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $otpData = $result->fetch_assoc();
        
        if ($otpData) {
            // Debugging Output - REMOVE after testing
            echo "<pre>";
            echo "Session Phone: " . $_SESSION['phone_number'] . "<br>";
            echo "Entered OTP: " . $otp . "<br>";
            echo "Stored Phone: " . $otpData['phone_number'] . "<br>";
            echo "Stored OTP: " . $otpData['otp_code'] . "<br>";
            echo "Expires At: " . $otpData['expires_at'] . "<br>";
            echo "Current Time: " . date("Y-m-d H:i:s") . "<br>";
            echo "</pre>";
        }

        // Validate OTP
        if ($otpData && strval($otpData['otp_code']) === strval($otp) && $otpData['expires_at'] > date("Y-m-d H:i:s")) {
            // ✅ OTP is correct! Update user phone number
            $stmt = $conn->prepare("UPDATE users SET phone_number = ? WHERE id = ?");
            $stmt->bind_param("si", $phone_number, $user_id);
            $stmt->execute();

            // ✅ Delete OTP after successful verification
            $stmt = $conn->prepare("DELETE FROM otp_codes WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // ✅ Redirect to voting dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid or expired OTP!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
    <h2>Enter the OTP sent to your phone</h2>
    <form method="post">
        <label for="otp">OTP Code:</label>
        <input type="text" name="otp" required>
        <button type="submit">Verify</button>
    </form>
    <p style="color: red;"><?php echo $message; ?></p>
</body>
</html>
