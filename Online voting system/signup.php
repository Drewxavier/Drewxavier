<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Dekut Online Voting</title>
    <style>
        /* Background styling */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('Ballot 2.jpeg') no-repeat center center/cover;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Container styling */
        .signup-container {
            background-color: rgba(247, 77, 35, 0.9); /* Transparent white */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            width: 400px;
            text-align: center;
        }

        .signup-container h1 {
            color: #006400; /* Dark green */
        }

        .signup-container p {
            font-size: 14px;
            color: #333;
        }

        .signup-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .signup-container button {
            width: 100%;
            padding: 10px;
            background-color: #008CBA; /* Blue */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .signup-container button:hover {
            background-color: #005f73;
        }

        .message {
            margin-top: 20px;
            font-size: 14px;
            color: #444;
        }

        .message a {
            color: #008CBA;
            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateForm() {
            var username = document.forms["signupForm"]["username"].value;
            var password = document.forms["signupForm"]["password"].value;

            var usernamePattern = /^C027-\d{2}-\d{4}\/\d{4}$/;
            if (!username.match(usernamePattern)) {
                alert("Invalid username format! Use C027-XX-XXXX/YYYY.");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }
        }
    </script>
</head>
<body>

    <div class="signup-container">
        <h1>Signup for Dekut Online Voting</h1>
        <p>Welcome to the official Dedan Kimathi Online Voting System!  
        Your vote matters—register now and be part of the democratic process.</p>

        <form name="signupForm" action="signup_process.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Enter your student ID (C027-XX-XXXX/YYYY)" required>
            <input type="password" name="password" placeholder="Enter a secure password" required>
            <button type="submit">Register</button>
        </form>

        <div class="message">
            <p>Already registered? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>
</html>
