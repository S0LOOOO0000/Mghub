<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mgcafe_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Sorry, we're experiencing issues.");
}

$conn->set_charset("utf8mb4");
?>