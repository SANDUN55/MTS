<?php
include 'database.php';
atabase_conectivity();
$sql = $logAction = '';
if(count($_POST)>0){
	if($_POST['type']==1){
		$bno = $_POST['bno'];
		$byr = trim($_POST['byr']);
		$code = strtoupper(trim($_POST['bcode']));
		$sql = "INSERT INTO `batch`( `b_no`, `b_yr`,`b_code`) VALUES ($bno,'$byr','$code')";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Batch " . $bno . $byr . $code;
		}
        else {
            echo "Error - Add Batch ";
            $logAction = "Error - Add Batch " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$id=$_POST['id'];
		$byr=$_POST['byr'];
		$bcode=$_POST['bcode'];
		$sql = "UPDATE `batch` SET `b_yr` = '$byr', `b_code` = '$bcode' WHERE b_no = $id";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Edit Batch " . $id . $byr . $bcode;
		}
        else {
            echo "Error - Edit Batch ";
            $logAction = "Error - Edit Batch " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM `batch` WHERE b_no = $id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
            $logAction = "Success - Delete Batch " . $id ;
		}
        else {
            echo "Error - Delete Batch ";
            $logAction = "Error - Edit Batch " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
	if(count($_POST)>0){
			if($_POST['type']==4){
				$id=$_POST['id'];
				$sql = "UPDATE batch SET `batchstatus`= 0 WHERE b_no = $id ";
				if (mysqli_query($conn, $sql)) {
					echo $id;
                    $logAction = "Success - Update Batch " . $id ;
				}
                else {
                    echo "Error - Update Batch ";
                    $logAction = "Error - Update Batch " . mysqli_error($conn);
                }
                $logAction = addslashes($logAction);
                writeLog($logAction);
                mysqli_close($conn);
			}
		}
	if(count($_POST)>0){
		if($_POST['type']==5){
			$id=$_POST['id'];
			$sql = "UPDATE batch SET `b_current_year`= (b_current_year+1)  WHERE b_no = $id ";
			if (mysqli_query($conn, $sql)) {
				echo $id;
                $logAction = "Success - Upgrade Batch " . $id ;
			}
            else {
                echo "Error - Upgrade Batch ";
                $logAction = "Error - Upgrade Batch " . mysqli_error($conn);
            }
            $logAction = addslashes($logAction);
            writeLog($logAction);
            mysqli_close($conn);
		}
	}
?>
