<?php
$database_server = 'localhost';
$database_user = 'gayathri';
$database_password = '1234@Gayathri';
$database = 'faculty';
$logdatabase = 'facultylog';
function database_conectivity() {
	global $conn;
	global $database_server;global $database_user;global $database_password;global $database;
	$conn = mysqli_connect($database_server, $database_user, $database_password ,$database) or die (mysqli_error());
}
function close_connection(){   
	global $conn;
	mysqli_close($conn);
}
function writeLog($txtaction){
	global $conn; global $logdatabase;
	database_conectivity();
	mysqli_select_db($conn,$logdatabase);
	//session_start();
	$user = $_SESSION['userMtsFom'];
	$ip = $_SERVER['REMOTE_ADDR'];
	mysqli_query($conn, "INSERT INTO user_log(usr_nm,activity,log_tm,usr_IP) VALUES ('$user','$txtaction',now(),'$ip')");
	close_connection();
	}
?>