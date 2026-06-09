<?php
$servername = "localhost";
$username = "stauser";
$password = "flgO[(u_rV(l.ZgZ";
$db = "staffmed";

// Create connection
$conn2 = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}
//echo "Connected successfully";
?>