<?php
define('DB_SERVER', '172.18.2.41');
define('DB_USERNAME', 'ransi');
define('DB_PASSWORD', '1234');
define('DB_NAME', 'labshedule');
$labconn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($labconn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
/*
define('DB_SERVER', '172.18.2.8');
define('DB_USERNAME', 'syslabreader');
define('DB_PASSWORD', 'jBGsQj8D4');
define('DB_NAME', 'labshedule');
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}*/
?>

