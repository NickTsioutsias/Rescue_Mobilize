<?php
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "rescue_mobilize";

// Create connection
$conn = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Do stuff here if we have connection
}

// echo "Connected successfully";
// $conn->close();
?>