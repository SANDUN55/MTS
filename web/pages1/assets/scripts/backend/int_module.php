<?php
include 'database.php';
database_conectivity();
$sql = $log_text = '';
if(count($_POST)>0){
	if($_POST['type']==1){
		$mb = $_POST['selectBatch'];
		$mn = $_POST['mcode'];
		$c1 = $_POST['selectAcademic'][0];
		$c2 = $_POST['selectAcademic'][1];
        //echo "ok";
		$sql = "INSERT INTO `batchmodule`( `b_no`, `m_code`, `cordi`, `cordi2`, `st_dt`) VALUES ($mb, '$mn', $c1, $c2, CURDATE() );";
        $rand = substr(uniqid('', true), -6);
        $userPw = password_hash($rand, PASSWORD_DEFAULT);
        $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth 
                    FROM staff WHERE st_id IN ($c1, $c2)";
		if (mysqli_multi_query($conn, $sql)) {
	//echo $sql;
			echo json_encode(array("statusCode"=>200));

            $logAction = "Add Initialize Module success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Initialize Module error " . addslashes($sql);
		}
        $logAction = addslashes($sql);
        writeLog($logAction);
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$id = explode(',', $_POST['id']);
		$batch = $id[0];
		$mcode = $id[1];
		$staffPos = $id[2];
		$staffCode = trim(implode('',$_POST['selectAcademic']));
        $rand = substr(uniqid('', true), -6);
        $userPw = password_hash($rand, PASSWORD_DEFAULT);
		if($staffPos == 'C1'){
            $sql = "UPDATE `batchmodule` SET `cordi`=$staffCode WHERE b_no=$batch AND m_code='$mcode';";
		}elseif($staffPos=='C2'){
            $sql = "UPDATE `batchmodule` SET `cordi2`=$staffCode WHERE b_no=$batch AND m_code='$mcode';";
        }
        $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth FROM staff WHERE st_id = $staffCode;";
		if (mysqli_multi_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Edit Initialize Module success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
        $logAction = addslashes($sql);
        writeLog($logAction);
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
    if($_POST['type']==3){
    	//id=30-BLIS1%2CT1&batch=30&mname=Blood%2C+Lymph+%26+Immune+1&academic=&selectNonAcademic%5B%5D=165&type=2
        $id=explode(',', $_POST['id']);
        $batchMod=$id[0];
        $staffPos=$id[1];
        $id2=explode('-', $batchMod);
        $bno=$id2[0];
        $mcode=$id2[1];
        $staffCode=trim(implode('',$_POST['selectNonAcademic']));
        $rand = substr(uniqid('', true), -6);
        $userPw = password_hash($rand, PASSWORD_DEFAULT);
        if($staffPos=='T1'){
            $sql = "UPDATE `batchmodule` SET `ttmng1`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T2'){
            $sql = "UPDATE `batchmodule` SET `ttmng2`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T3'){
            $sql = "UPDATE `batchmodule` SET `ttmng3`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T4'){
            $sql = "UPDATE `batchmodule` SET `ttmng4`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T5'){
            $sql = "UPDATE `batchmodule` SET `ttmng5`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T6'){
            $sql = "UPDATE `batchmodule` SET `ttmng6`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        }
        $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '5' as cat, '$userPw' as usrpwd, 'l' as auth FROM staff WHERE st_id = $staffCode;";
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Add Timetable Managers success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Add Timetable  Managers error " . addslashes($sql);
        }
        $logAction = addslashes($sql);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==4){
        //30-CARE1,T1
        $id = $_POST['id'];
        $id2 = explode(',', $id);
        $batchMod = $id2[0];
        $staffPos = $id2[1];
        $id3 = explode('-', $batchMod);
        $bno = $id3[0];
        $mcode = $id3[1];
        if($staffPos=='T1'){
            $sql = "UPDATE `batchmodule` SET `ttmng1`=0 WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T2'){
            $sql = "UPDATE `batchmodule` SET `ttmng2`=0 WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T3'){
            $sql = "UPDATE `batchmodule` SET `ttmng3`=0 WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T4'){
            $sql = "UPDATE `batchmodule` SET `ttmng4`=0 WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T5'){
            $sql = "UPDATE `batchmodule` SET `ttmng5`=0 WHERE b_no=$bno AND m_code='$mcode';";
        }elseif($staffPos=='T6'){
            $sql = "UPDATE `batchmodule` SET `ttmng6`=0 WHERE b_no=$bno AND m_code='$mcode';";
        }
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Edit Timetable Managers success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Edit Timetable Managers error " . addslashes($sql);
        }
        $logAction = addslashes($sql);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==5){
        $id = $_POST['id'];
        $id2 = explode(',', $id);
        $batch = $id2[0];
        $mod = $id2[1];
        $val = $id2[2];
        $dt = $_POST['classDate'];
        $modDate = date ('Y-m-d', strtotime($dt));
        if($val=='C1'){
            $sql = "UPDATE `batchmodule` SET `st_dt` = '$modDate' WHERE b_no=$batch AND m_code='$mod';";
        }elseif($val=='C2'){
            $sql = "UPDATE `batchmodule` SET `en_dt` = '$modDate' WHERE b_no=$batch AND m_code='$mod';";
        }
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Add Timetable Date success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Add Timetable Date error " . addslashes($sql);
        }
       $logAction = addslashes($sql);
       writeLog($logAction);
       mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==6){
        $id = $_POST['id'];
        $id2 = explode(',', $id);
        $batch = $id2[0];
        $mod = $id2[1];
        $val = $id2[2];
        $dt = $_POST['classDate'];
        $modDate = date ('Y-m-d', strtotime($dt));
        if($val=='C1'){
            $sql = "UPDATE `batchmodule` SET `st_dt` = '$modDate' WHERE b_no=$batch AND m_code='$mod';";
        }elseif($val=='C2'){
            $sql = "UPDATE `batchmodule` SET `en_dt` = '$modDate' WHERE b_no=$batch AND m_code='$mod';";
        }
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Edit Timetable Date success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Edit Timetable Date error " . addslashes($sql);
        }
        $logAction = addslashes($sql);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==7){
        //bno=30&mco=ALNU1&mnm=&type=7
        $batch = $_POST['bno'];
        $mod = $_POST['mco'];
        $sql = "UPDATE `batchmodule` SET `ttprogress` = 1 WHERE b_no=$batch AND m_code='$mod'";
        if(mysqli_query($conn, $sql)){
            echo json_encode(array("statusCode"=>200));
            $logAction = "Confirm Module Timetable success " . addslashes($sql);
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Confirm Module Timetable error " . addslashes($sql);
        }
        $logAction = addslashes($sql);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==8){
        $id = $_POST['id'];
        $id2 = explode(',', $id);
        $batch = $id2[0];
        $mod = $id2[1];

        $sql = "UPDATE `batchmodule` SET `comp_on` = now(), `ttprogress` = 2 WHERE b_no=$batch AND m_code='$mod'";
        if(mysqli_query($conn, $sql)){
            echo json_encode(array("statusCode"=>200));
            $logAction = "End Module success " . addslashes($sql);
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "End Module error " . addslashes($sql);
        }
        $logAction = addslashes($sql);
        writeLog($logAction);
        mysqli_close($conn);
    }
}

?>
