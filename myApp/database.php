<?php
/**
 * Database Configuration
 * Prevent multiple inclusion errors
 */

// Original credentials (you can keep them commented for reference)
# define('DB_SERVER', 'localhost');
# define('DB_USERNAME', 'mtsadmin');
# define('DB_PASSWORD', 'S]Le-Eg*4736');
# define('DB_NAME', 'mts');

/* ================== Active Configuration ================== */

if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'localhost');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');           // Change if needed
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');               // Put password if any
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'mts');            // ← Change to your actual DB name
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

/* ================== Database Connection ================== */

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect to MySQL. " . mysqli_connect_error());
}
?>