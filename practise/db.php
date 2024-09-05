<?php
$servername = "localhost";
$username = "silveradmin";
$password = "admin1234"; // Set your MySQL password here
$dbname = "sempractise";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
