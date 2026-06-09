<?php
// Direct test to see what load_timetable.php returns
$_GET['custom_param1'] = 34;
$_GET['custom_param2'] = 'ALIM2';
$_GET['custom_param3'] = 1;

header('Content-Type: application/json');
include 'load_timetable.php';
?>
