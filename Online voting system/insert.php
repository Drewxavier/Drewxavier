<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$password = "Admin#01"; // Change this to your desired password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Hashed Password: " . $hashed_password . PHP_EOL;
?>
