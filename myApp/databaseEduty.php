<?php
$servername = "172.18.2.224";
$username = "exmduty";
$password = "uNawrN1Ve@";
$db = "med_sis";

// Create connection
$conn3 = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn3->connect_error) {
    die("Connection failed: " . $conn3->connect_error);
}
//echo "Connected successfully";
?>
