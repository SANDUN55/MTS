<?php
include 'database.php';
database_conectivity();
if(count($_POST)>0){
	if($_POST['type']==1){
		$bno = $_POST['bno'];
		$byr = trim($_POST['byr']);
		$code = strtoupper(trim($_POST['bcode']));
		$sql = "INSERT INTO `batch`( `b_no`, `b_yr`,`b_code`,`b_current_year`) VALUES ($bno,'$byr','$code',0)";
		echo $sql;
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Batch " . $bno . $byr . $code ;
		}
		else {
			echo "Error - Add Batch " ;
            $logAction = "Error - Add Batch " . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$id = $_POST['id'];
		$byr = $_POST['byr'];
		$bcode = $_POST['bcode'];
		$sql = "UPDATE `batch` SET `b_yr`='$byr', `b_code`='$bcode' WHERE b_no=$id";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Edit Batch  " . $bno . $byr . $code;
		} 
		else {
			echo "Error - Edit Batch " ;
            $logAction = "Error  - Edit Batch " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM `batch` WHERE b_no=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
            $logAction = "Success  - Delete Batch " . $id;
		} 
		else {
			echo "Error - Delete Batch " ;
            $logAction = "Error - Delete Batch " .  mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
    if($_POST['type']==4){
        $id=$_POST['id'];
        $sql = "UPDATE batch SET `batchstatus`=0 WHERE b_no=$id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Disable Batch  " . $id;
        }
        else {
            echo "Error - Disable Batch ";
            $logAction = "Error - Disable Batch " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==5){
        $id=$_POST['id'];
        $sql = "UPDATE batch SET `b_current_year`=(b_current_year+1)  WHERE b_no=$id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Upgrade Batch  " . $id;
        }
        else {
            echo "Error - Disable Batch ";
            $logAction = "Error - Upgrade Batch " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==6){
        $bno = $_POST['selectBatch'];
            $stno = strtoupper(trim($_POST['stno']));
            $ino = strtoupper(trim($_POST['ino']));
            $nm = strtoupper(trim($_POST['nm']));
            $ntid = trim($_POST['netid']);
            $eml = trim($_POST['eml']);
            $rand = substr(uniqid('', true), -6);
            $userPw = password_hash($rand, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `batchreps`( `b_no`,  `st_no`, `st_nm`, `net_id`,  `st_eml`) VALUES ($bno, '$stno', '$nm', '$ntid', '$eml');";
            $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) VALUES ('$ntid', '7' , '$userPw' , 'l' )";
            if (mysqli_multi_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
                $logAction = " Success - Add  Batch Representative " . $bno . $stno . $nm . $ntid . $eml;
            }
            else {
                echo "Error - Add  Batch Representative ";
                $logAction = "Error - Add  Batch Representative " . mysqli_error($conn);
            }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==7){
        $id=$_POST['id'];
		$bno = $_POST['selectBatch'];
        $stno = strtoupper(trim($_POST['stno']));
        $ino = strtoupper(trim($_POST['ino']));
        $nm = strtoupper(trim($_POST['nm']));
        $ntid = trim($_POST['netid']);
        $eml = trim($_POST['eml']);
        $sql = "UPDATE `batchreps` SET `b_no`= $bno, `st_no`= '$stno', `st_nm`= '$nm', `net_id`= '$ntid', `st_eml`= '$eml' WHERE `rep_id`= $id;";
        $rand = substr(uniqid('', true), -6);
        $userPw = password_hash($rand, PASSWORD_DEFAULT);
        $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) VALUES ('$ntid', '7' , '$userPw' , 'l' );";
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = " Success - Edit  Batch Representative " . $bno . $stno . $nm . $ntid . $eml;
        }
        else {
            echo "Error - Add  Batch Representative ";
            $logAction = "Error - Edit  Batch Representative " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
    if($_POST['type']==10){
        $id=$_POST['id'];
        $sql = "UPDATE batchreps SET `st_status`= 0  WHERE rep_id = $id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Disable  Batch Representative  " . $id;
        }
        else {
            echo "Error - Disable  Batch Representative ";
            $logAction = "Error - Disable  Batch Representative " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
//BATCH REPRESENTATIVES FOR MODULES
if(count($_POST)>0){
    if($_POST['type']==8){
        $batch = $_POST['selectBatch'];
        $module = $_POST['mcode'];
        $repType = $_POST['repno'];
        $repid = $_POST['brep'];
        if($repType=='R1'){
            $sql = "UPDATE `batchmodule` SET `rep1`= $repid WHERE b_no=$batch AND m_code='$module';";
        }elseif($repType=='R2'){
            $sql = "UPDATE `batchmodule` SET `rep2`= $repid WHERE b_no=$batch AND m_code='$module';";
        }elseif($repType=='R3'){
            $sql = "UPDATE `batchmodule` SET `rep3`= $repid WHERE b_no=$batch AND m_code='$module';";
        }elseif($repType=='R4'){
            $sql = "UPDATE `batchmodule` SET `rep4`= $repid WHERE b_no=$batch AND m_code='$module';";
        }
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Batch Representative to Module " . $repType . $batch .$module ;
        }
        else {
            echo "Error - Add Batch Representative to Module ";
            $logAction = "Error - Add Batch Representative to Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==9){
        //33,ALNU1,R3
        $id = $_POST['id'];
        $id2 = explode(',', $id);
        $batch = $id2[0];
        $module = $id2[1];
        $repType = $id2[2];
        if($repType=='R1'){
            $sql = "UPDATE `batchmodule` SET `rep1`= 0 WHERE b_no=$batch AND m_code='$module';";
        }elseif($repType=='R2'){
            $sql = "UPDATE `batchmodule` SET `rep2`= 0 WHERE b_no=$batch AND m_code='$module';";
        }elseif($repType=='R3'){
            $sql = "UPDATE `batchmodule` SET `rep3`= 0 WHERE b_no=$batch AND m_code='$module';";
        }elseif($repType=='R4'){
            $sql = "UPDATE `batchmodule` SET `rep4`= 0 WHERE b_no=$batch AND m_code='$module';";
        }
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Remove Batch Representative to Module   " . $repType . $batch . $module  ;
        }
        else {
            echo "Error - Remove Batch Representative to Module ";
            $logAction = "Error - Remove Batch Representative to Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
?>
