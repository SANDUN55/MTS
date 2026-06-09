<?php
// Test what JSON is being returned for confirmed classes
$_GET['custom_param1'] = 34;
$_GET['custom_param2'] = 'ALIM2';
$_GET['custom_param3'] = 1;

// Capture output
ob_start();
include 'load_timetable.php';
$output = ob_get_clean();

// Pretty print JSON
header('Content-Type: application/json');
echo $output;
?>
