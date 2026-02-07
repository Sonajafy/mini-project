<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "bus_booking_system"; // Must match the name you created in phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>