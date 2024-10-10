<?php
$servername = "localhost";  // Change if necessary
$username = "root";         // Your DB username
$password = "";             // Your DB password
$dbname = "recharge_system";  // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
