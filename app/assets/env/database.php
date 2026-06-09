<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'mtsadmin');
define('DB_PASSWORD', 'S]Le-Eg*4736');
define('DB_NAME', 'mts');
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>