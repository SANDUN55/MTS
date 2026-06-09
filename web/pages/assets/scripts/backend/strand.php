<?php
include 'database.php';
database_conectivity();
$sql = $logAction = '';
if(count($_POST)>0){
	if($_POST['type']==1){
		$bno = $_POST['bno'];
		$byr = trim($_POST['byr']);
		$code = strtoupper(trim($_POST['bcode']));
		$sql = "INSERT INTO `batch`( `b_no`, `b_yr`,`b_code`) VALUES ($bno,'$byr','$code')";

		if (mysqli_query($conn, $sql)) {

			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$id=$_POST['id'];
		$byr=$_POST['byr'];
		$bcode=$_POST['bcode'];
		$sql = "UPDATE `batch` SET `b_yr`='$byr', `b_code`='$bcode' WHERE b_no=$id";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM `batch` WHERE b_no=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
if($_POST['type']==4){
$id=$_POST['id'];
$sql = "UPDATE batch SET `batchstatus`=0 WHERE b_no=$id ";
if (mysqli_query($conn, $sql)) {
echo $id;
}
else {
echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
}
}
if(count($_POST)>0){
    if($_POST['type']==5){
        $id=$_POST['id'];
        $sql = "UPDATE batch SET `b_current_year`=(b_current_year+1)  WHERE b_no=$id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
?>
