<?php
session_start(); // Start session at the very top

// Redirect logged-in users away from login page
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dekut Online Voting: Login</title>
    <style>
        /* Background styling */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('voting.jpeg') no-repeat center center/cover;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Dekut Logo */
        .logo-container {
            text-align: center;
            margin-bottom: 30px; /* Space between logo and login box */
        }

        .logo-container img {
            width: 250px;
            height: auto;
        }

        /* Login container */
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            width: 420px;
            text-align: center;
        }

        .login-container h1 {
            color: #006400;
        }

        .login-container p {
            font-size: 15px;
            color: #333;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 15px;
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
            var username = document.forms["loginForm"]["username"].value;
            var password = document.forms["loginForm"]["password"].value;

            if (username == "" || password == "") {
                alert("Both fields must be filled out.");
                return false;
            }
        }
    </script>
</head>
<body>

    <!-- Dekut Logo Above Login Container -->
    <div class="logo-container">
        <img src="dekutlogo.jpeg" alt="Dekut Logo">
    </div>

    <div class="login-container">
        <h1>Welcome to Dekut Online Voting</h1>
        <p>Your voice matters! Securely log in to vote and participate in democracy.</p>

        <form name="loginForm" action="login_process.php" method="post" onsubmit="return validateForm()" autocomplete="off">
            <input type="text" name="username" placeholder="Enter your student ID" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>

        <div class="message">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            <p><a href="https://www.dkut.ac.ke/index.php/component/k2/item/604-support-contacts">Forgot password?</a></p>
        </div>
    </div>

</body>
</html>
