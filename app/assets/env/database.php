<?php

// Main Database
define('DB1_SERVER', 'localhost');
define('DB1_USERNAME', 'root');
define('DB1_PASSWORD', '');
define('DB1_NAME', 'mts');

$conn = mysqli_connect(
    DB1_SERVER,
    DB1_USERNAME,
    DB1_PASSWORD,
    DB1_NAME
);

if (!$conn) {
    die("Main DB Connection Failed: " . mysqli_connect_error());
}


// Lab Schedule Database
define('DB2_SERVER', '172.18.2.41');
define('DB2_USERNAME', 'ransi');
define('DB2_PASSWORD', '1234');
define('DB2_NAME', 'labshedule');

$conn2 = mysqli_connect(
    DB2_SERVER,
    DB2_USERNAME,
    DB2_PASSWORD,
    DB2_NAME
);

if (!$conn2) {
    die("Lab DB Connection Failed: " . mysqli_connect_error());
}

?>