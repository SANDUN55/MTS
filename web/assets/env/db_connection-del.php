<?php
$database_server='localhost';
$database_user='root';
$database_password='';
$database='faculty';
$logdatabase='facultylog';
function database_conectivity() {
	global $fcon;
	global $database_server;global $database_user;global $database_password;global $database;
	$fcon = mysqli_connect($database_server, $database_user, $database_password ,$database) or die (mysqli_error());
}
function close_connection(){   
	global $fcon;
	mysqli_close($fcon);
}
function writeLog($txtaction){
	global $fcon;global $logdatabase;
	database_conectivity();
	mysqli_select_db($fcon,$logdatabase);
	session_start();
	$user=$_SESSION['medusr'];
	$ip=$_SERVER['REMOTE_ADDR'];
	mysqli_query($fcon, "INSERT INTO user_log(usr_nm,activity,log_tm,usr_IP) VALUES ('$user','$txtaction',now(),'$ip')");
	close_connection();
	}
	

?>

