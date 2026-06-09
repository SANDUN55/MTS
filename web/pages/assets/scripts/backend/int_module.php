<?php
include 'database.php';
database_conectivity();
$sql = $logAction = '';
if(count($_POST)>0){
	if($_POST['type']==1){
		$mb = $_POST['selectBatch'];
		$mn = $_POST['mcode'];
		$c1 = $_POST['selectAcademic'][0];
		//echo $c1;
		// TWO CHAIR $c2 = $_POST['selectAcademic'][1];
        //echo "ok";
        // TWO CHAIR        $sql = "INSERT INTO `batchmodule`( `b_no`, `m_code`, `cordi`, `cordi2`, `st_dt`) VALUES ($mb, '$mn', $c1, $c2, CURDATE() );";

        $sql = "INSERT INTO `batchmodule`( `b_no`, `m_code`, `cordi`, `st_dt`) VALUES ($mb, '$mn', $c1, CURDATE() );";
        $rand = substr(uniqid('', true), -6);
        $userPw = password_hash($rand, PASSWORD_DEFAULT);
        /* TWO CHAIR $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth
                    FROM staff WHERE st_id IN ($c1, $c2)";*/
        $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '4' as cat, '$userPw' as usrpwd, 'l' as auth 
                    FROM staff WHERE st_id = $c1";
        $mnm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT( m_name, ' Phase ', m_phase) AS m_name FROM module WHERE m_code = '$mn' ;"));
        $modnm = $mnm['m_name'];
        $steml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM staff WHERE st_id = $c1"));
        $seml = $steml['st_em'];
        $toeml = 'medu@kln.ac.lk, ' . $seml;
        $to = $toeml  ;
     if (mysqli_multi_query($conn, $sql)) {
            $subject = "Convener Assigned - MTS, Faculty of Medicine";
            $message = "Dear Sir/Madam, <br><br>
The module ". $modnm . " of batch " . $mb . " is due to be started soon. <br>
Please access the online Module Timetable System to create the tentative and final timetable. <br><br>
Thank You. <br>
Department of Medical Education. <br><br> This is an automatically generated email";
            $headers = 'From: "Department of Medical Education" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f ictcmed@kln.ac.lk");
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Initialize Module " . $mb . $mn . $c1 ;
		} 
		else {
			echo "Error - Initialize Module ";
            $logAction = "Error - Add Initialize Module " . $mb . $mn . $c1 . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
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
		//echo $sql;
        $mnm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT( m_name, ' Phase ', m_phase) AS m_name FROM module WHERE m_code = '$mcode' ;"));
        $modnm = $mnm['m_name'];
        $steml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM staff WHERE st_id = $staffCode"));
        $seml = $steml['st_em'];
        $to = 'medu@kln.ac.lk, ' . $seml ;
		if (mysqli_multi_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Edit Initialize Module ". $id .$staffCode ;

            $subject = "Convener Changed - MTS, Faculty of Medicine";
            $message = "Dear Sir/Madam, <br><br>
You have assigned as the convener of the module ". $modnm . " of batch " . $mb . ". <br>
Please access the online Module Timetable System to add/edit tentative/final timetable. <br><br>
Thank You. <br>
Department of Medical Education. <br><br> This is an automatically generated email";
            $headers = 'From: "Department of Medical Education" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f ictcmed@kln.ac.lk");
		} 
		else {
			echo "Error -  Edit Initialize Module ";
            $logAction = "Error -  Edit Initialize Module " . $id .$staffCode . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
    if($_POST['type']==3){
        $id = explode(',', $_POST['id']);
      // print_r($_POST);
        $bno = $_POST['batch'];
        $mcode = $id[0];
        $dep = $_POST['dp_u'];
        $staffCode = $_POST['selectNonAcademic'];
        $rand = substr(uniqid('', true), -6);
        $userPw = password_hash($rand, PASSWORD_DEFAULT);
        $sql = "UPDATE `batchmodule` SET `ttmng1`=$staffCode WHERE b_no=$bno AND m_code='$mcode';";
        $sql .= "INSERT IGNORE INTO `users` (`user_nm`, `user_cat`, `user_pw`, `user_auth`) SELECT username, '5' as cat, '$userPw' as usrpwd, 'l' as auth FROM staff WHERE st_id = $staffCode;";
         //echo $sql;
        $mnm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT( m_name, ' Phase ', m_phase) AS m_name FROM module WHERE m_code = '$mcode' ;"));
        $modnm = $mnm['m_name'];
        $steml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM staff WHERE st_id = $staffCode"));
        $seml = $steml['st_em'];
        $to = 'medu@kln.ac.lk, ' . $seml ;
        if (mysqli_multi_query($conn, $sql)) {
             echo json_encode(array("statusCode"=>200));

            $subject = "Timetable Manager Assigned - MTS, Faculty of Medicine";
            $message = "<br>
You have assigned as the timetable manager of the module ". $modnm . " of batch " . $mb . ". <br>
Please access the online Module Timetable System to add/edit tentative/final timetable. <br><br>
Thank You. <br>
Module Convener. <br><br> This is an automatically generated email";
            $headers = 'From: "Department of Medical Education" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f ictcmed@kln.ac.lk");
             $logAction = "Success - Add Timetable Managers " . $id . $bno . $staffCode;
         }
         else {
             echo "Error -  Add Timetable Managers ";
             $logAction = "Add Timetable  Managers error " . $id .$bno . $staffCode . mysqli_error($conn);
         }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);

    	//id=30-BLIS1%2CT1&batch=30&mname=Blood%2C+Lymph+%26+Immune+1&academic=&selectNonAcademic%5B%5D=165&type=2
        //FOR SIX TIMETABLE MANAGERS
       /* $id=explode(',', $_POST['id']);
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
        mysqli_close($conn);*/

    }
}
if(count($_POST)>0){
    if($_POST['type']==4){
        //30-CARE1
        $id2 = explode('-', $_POST['id']);
        $bno = $id2[0];
        $mcode = $id2[1];
        $sql = "UPDATE `batchmodule` SET `ttmng1`=0 WHERE b_no=$bno AND m_code='$mcode';";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Remove Timetable Managers " . $id2;
        }
        else {
            echo "Error - Remove Timetable Managers";
            $logAction = "Error - Timetable Managers error " . $id2 . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
       /* $id = $_POST['id'];
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
        mysqli_close($conn);*/
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
        $val2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ttprogress FROM `batchmodule` WHERE b_no = $batch AND m_code = '$mod'"));
        $ttprogress = $val2['ttprogress'];
         if($ttprogress == '0'){
            if($val=='C1'){
                 $sql = "UPDATE `batchmodule` SET `st_dt` = '$modDate', `ttprogress` = 1 WHERE b_no = $batch AND m_code = '$mod';";
            }elseif($val=='C2'){
                 $sql = "UPDATE `batchmodule` SET `en_dt` = '$modDate', `ttprogress` = 1 WHERE b_no=$batch AND m_code='$mod';";
            }
        }else{
             if($val=='C1'){
                 $sql = "UPDATE `batchmodule` SET `st_dt` = '$modDate' WHERE b_no = $batch AND m_code = '$mod';";
            }elseif($val=='C2'){
                 $sql = "UPDATE `batchmodule` SET `en_dt` = '$modDate' WHERE b_no=$batch AND m_code='$mod';";
            }
        }
/*        if($val=='C1'){
            $sql = "UPDATE `batchmodule` SET `st_dt` = '$modDate' WHERE b_no = $batch AND m_code = '$mod';";
        }elseif($val=='C2'){
            $sql = "UPDATE `batchmodule` SET `en_dt` = '$modDate' WHERE b_no=$batch AND m_code='$mod';";
            //$sql = "UPDATE `batchmodule` SET `en_dt` = '$modDate', ttprogress = 1 WHERE b_no=$batch AND m_code='$mod';";
        }*/
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Define Module " . $id . $modDate;
        }
        else {
            echo "Error - Add Timetable Date ";
            $logAction = "Error -  Define Module " . mysqli_error($conn);
        }
       $logAction = addslashes($logAction);
       writeLog($logAction);
       mysqli_close($conn);
    }
}
/*if(count($_POST)>0){
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
            $logAction = "Success - Edit Timetable Date " . $batch . $mod;
        }
        else {
            echo "Error - Edit Timetable Date" ;
            $logAction = "Error - Edit Timetable Date " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}*/
if(count($_POST)>0){
    if($_POST['type']==7){
        //bno=30&mco=ALNU1&mnm=&type=7
        $batch = $_POST['bno'];
        $mod = $_POST['mco'];
        $sql = "UPDATE `batchmodule` SET `ttprogress` = 3 WHERE b_no=$batch AND m_code='$mod'";
        $mnm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT( m_name, ' Phase ', m_phase) AS m_name FROM module WHERE m_code = '$mod' ;"));
        $modnm = $mnm['m_name'];
        $conv = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM batchmodule JOIN staff on st_id = cordi WHERE b_no = $batch AND m_code = '$mod' ;"));
        $conveml = $conv['st_em'];
        $steml = mysqli_fetch_assoc(mysqli_query($conn, "select GROUP_CONCAT(DISTINCT (st_em)) AS eml from classtopics
                JOIN classschedules ON class_topic_id = topic_id JOIN staff ON st_id = staff  where b_no = $batch AND m_code = '$mod' AND class_status = 1 AND staff<>classtopics.dep_code;"));
        $seml = $steml['eml'];
        $to = 'medu@kln.ac.lk, ' . $seml . ',' . $conveml ;
        if(mysqli_query($conn, $sql)){
            echo json_encode(array("statusCode"=>200));

            $subject = "Batch " . $batch . $modnm . " confirmed timetable - Faculty of Medicine";
            $message = "Hi,  <br><br>
The confirmed timetable of batch " . $batch . " - "  .  $modnm . " is now published. Please use online timetable system to request the changes to the timetable.<br>
Many thanks. <br>
Module Convener. <br><br> This is an automatically generated email";
            $headers = 'From: "Department of Medical Education" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f ictcmed@kln.ac.lk");
            $logAction = "Success - Confirm Module Timetable " . $batch . $mod;
        }
        else{
            echo "Error - Confirm Module Timetable";
            $logAction = "Error - Confirm Module Timetable" . $batch . $mod . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
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
        $sql = "UPDATE `batchmodule` SET `comp_on` = now(), `ttprogress` = 4 WHERE b_no=$batch AND m_code='$mod'";
        $mnm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT( m_name, ' Phase ', m_phase) AS m_name FROM module WHERE m_code = '$mod' ;"));
        $modnm = $mnm['m_name'];
        $steml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM staff WHERE st_id IN ( SELECT cordi FROM batchmodule WHERE b_no = $batch AND m_code = '$mod');"));
        $seml = $steml['st_em'];
        $to = 'medu@kln.ac.lk, ' . $seml ;
        if(mysqli_query($conn, $sql)){
            echo json_encode(array("statusCode"=>200));

            $subject = "Complete Module - MTS, Faculty of Medicine";
            $message = "This is an automatically generated email <br><br>
You have successfully  completed batch " . $batch . " - "  .  $modnm . " Module <br>Thank You<br>Department of Medical Education";
            $headers = 'From: "Department of Medical Education" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f ictcmed@kln.ac.lk");
            $logAction = "Success - End Module " . $batch . $mod;
        }
        else{
            echo "Error: End Module" ;
            $logAction = "Error - End Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if($_POST['type'] == 9){
    $id = $_POST['id'];
    $id2 = explode(',', $id);
    $batch = $id2[0];
    $mod = $id2[1];
    $sql = "UPDATE `batchmodule` SET `ini_on` = now(), `ttprogress` = 2 WHERE b_no=$batch AND m_code='$mod'";
    $mnm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT( m_name, ' Phase ', m_phase) AS m_name FROM module WHERE m_code = '$mod' ;"));
    $modnm = $mnm['m_name'];
    $conv = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM batchmodule JOIN staff on st_id = cordi WHERE b_no = $batch AND m_code = '$mod' ;"));
    $conveml = $conv['st_em'];
    $steml = mysqli_fetch_assoc(mysqli_query($conn, "select GROUP_CONCAT(DISTINCT (st_em)) AS eml from classtopics
                JOIN classschedules ON class_topic_id = topic_id JOIN staff ON st_id = staff  where b_no = $batch AND m_code = '$mod' AND class_status = 1;"));
    $seml = $steml['eml'];
    $to = 'medu@kln.ac.lk, ' . $seml . ',' . $conveml  ;
    if(mysqli_query($conn, $sql)){
        echo json_encode(array("statusCode"=>200));

        $subject = "Batch " . $batch . $modnm . " tentative timetable - Faculty of Medicine";
        $message = "This is an automatically generated email <br><br>
The tentative timetable of batch " . $batch . " - "  .  $modnm . " is available. If changes are to be requested, please use the request form in the online timetable system";
        $headers = 'From: "Department of Medical Education" <medu@kln.ac.lk>'. "\r\n" ;
        $headers .= "Content-Type: text/html;";
        mail($to, $subject, $message, $headers,"-f ictcmed@kln.ac.lk");
        $logAction = "Success - Publish Tentative " . $batch . $mod;
    }
    else{
        echo "Error - Publish Tentative " ;
        $logAction = "Error  - Publish Tentative " . mysqli_error($conn);
    }
    $logAction = addslashes($logAction);
    writeLog($logAction);
    mysqli_close($conn);
}
?>
