<?php
include 'database.php';
database_conectivity();
if(count($_POST)>0){
	if($_POST['type']==1){
		$bno = $_POST['bno'];
		$byr = trim($_POST['byr']);
		$code = strtoupper(trim($_POST['bcode']));
		$sql = "INSERT INTO `batch`( `b_no`, `b_yr`,`b_code`,`b_current_year`) VALUES ($bno,'$byr','$code',0)";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Add Batch success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Add Batch error " . addslashes($sql);
		}
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
            $logAction = "Edit Batch success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Edit Batch error " . addslashes($sql);
		}
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
            $logAction="Delete Batch success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction="Delete Batch error " . mysqli_error($conn);
		}
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
            $logAction = "Disable Batch success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction="Disable Batch error " . mysqli_error($conn);
        }
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
            $logAction = "Upgrade Batch success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction="Upgrade Batch error " . mysqli_error($conn);
        }
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
       // $rand = substr(uniqid('', true), -6);
        //$userPw = password_hash($rand, PASSWORD_DEFAULT);
        //$sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth FROM staff WHERE st_id IN ($c1, $c2)";

        $sql = "INSERT INTO `batchreps`( `b_no`, `st_code`, `st_no`, `st_nm`, `net_id`,  `st_eml`) VALUES ($bno, '$ino', '$stno', '$nm', '$ntid', '$eml')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Add  Batch Representative " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction="Add Batch Representative error " . mysqli_error($conn);
        }
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
        $sql = "UPDATE `batchreps` SET `b_no`= $bno, `st_code`= '$ino', `st_no`= '$stno', `st_nm`= '$nm', `net_id`= '$ntid', `st_eml`= '$eml' WHERE `rep_id`= $id";
       // $rand = substr(uniqid('', true), -6);
       // $userPw = password_hash($rand, PASSWORD_DEFAULT);
       // $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth FROM staff WHERE st_id IN ($c1, $c2)";
       // if (mysqli_multi_query($conn, $sql)) {
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Edit  Batch Representative " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction="Edit Batch Representative error " . mysqli_error($conn);
        }
        writeLog($logAction);
        mysqli_close($conn);
    }
}
?>
