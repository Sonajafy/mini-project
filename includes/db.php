<?php
// includes/db.php
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "bus_booking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start(); // Start session on every page
?>