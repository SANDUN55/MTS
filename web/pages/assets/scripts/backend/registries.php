<?php
include 'database.php';
database_conectivity();
//HOLIDAYS REGISTRY
if(count($_POST)>0){
	if($_POST['type']==1){
		$start = $_POST['holStDate'];
        $end = $_POST['holEnDate'];
        $phpStTime = date( 'Y-m-d', strtotime($start) );
        $phpEnTime = date( 'Y-m-d', strtotime($end) );
        $des = addslashes(trim($_POST['hdes']));
		$sql = "INSERT INTO `holidays`( `startDt`, `enddt`, `holidayDes`) VALUES ( '$phpStTime', '$phpEnTime', '$des')";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Holiday ". ' - ' . $phpStTime . ' - ' .$phpEnTime. ' - ' .$des ;
		} 
		else {
			echo "Error - Add Holiday " ;
            $logAction = "Error - Add Holiday " . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
        $id=$_POST['id'];
		$start = $_POST['holStDate'];
        $end = $_POST['holEnDate'];
        $phpStTime = date( 'Y-m-d', strtotime($start) );
        $phpEnTime = date( 'Y-m-d', strtotime($end) );
        $des = addslashes(trim($_POST['hdes']));
		$sql = "UPDATE `holidays` SET `startDt` = '$phpStTime', `enddt` = '$phpEnTime', `holidayDes` =  '$des' WHERE hid=$id";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
            $logAction = "success - Edit Holiday " .  ' - ' . $phpStTime . ' - ' .$phpEnTime. ' - ' .$des;
		} 
		else {
			echo "Error -  Edit Holiday ";
            $logAction = "Error -  Edit Holiday " . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM `holidays` WHERE hid=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
            $logAction = "Success - Delete Holiday  " . $id;
		} 
		else {
			echo "Error - Delete Holiday " ;
            $logAction = "Error - Delete Holiday " . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
//VISITING STAFF REGISTRY
if(count($_POST)>0){
    if($_POST['type']==5){
        //print_r($_POST);
        $title = $_POST['title'];
        $fname = trim($_POST['fname']);
        $sname = trim($_POST['sname']);
        $email = trim($_POST['eml']);
        $dep = trim($_POST['depart']);
        $stcode = trim($_POST['stcode']);
        $uname = trim($_POST['netid']);
        //$staffDBcon = mysqli_connect('localhost', 'gayathri', '1234@Gayathri' , 'staffdb') or die (mysqli_error());
        //echo mysqli_error($staffDBcon);
        $sql = "INSERT INTO `staffvisiting`( `st_id`, `t_nm`, `firstname`, `surname`, `staffcode`, `st_em`, `desig`, `dep_code`, `div_nm`, `st_cat`,`staff_cat_nm`, `onleave`, `username` ) 
            VALUES ( (SELECT MAX(s1.st_id)+1  FROM staff s1), '$title', '$fname', '$sname', '$stcode', '$email', 'Visiting Lecturer', $dep , (SELECT div_nm  FROM divisions WHERE div_id =  $dep), 4, 'Visiting', 0, '$uname')";
        //echo $sql;
        //        if (mysqli_query($staffDBcon, $sql)) {
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Visiting Staff " . $title .  $fname . $sname . $stcode . $email . $dep;
        //echo $sql;
        }
        else {
            echo "Error - Add Visiting Staff ";
            $logAction =" Error - Add Visiting Staff " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==6){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $fname = trim($_POST['fname']);
        $sname = trim($_POST['sname']);
        $email = trim($_POST['eml']);
        $dep = trim($_POST['depart']);
        $stcode = trim($_POST['stcode']);
        $uname = trim($_POST['netid']);
        $sql = "UPDATE `staffvisiting` SET `t_nm` = '$title', `firstname` = '$fname', `surname` = '$sname', `st_em` = '$email', `staffcode` ='$stcode', `dep_code` ='$dep', `div_nm` =  (SELECT div_nm  FROM divisions WHERE div_id =  $dep), `username`=  '$uname'  WHERE `st_id` = $id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success  - Edit Visiting Staff " . $title .  $fname . $sname . $stcode . $email . $dep;
        }
        else {
            echo "Error - Edit Visiting Staff " ;
            $logAction = "Error - Edit Visiting Staff error " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==7){
        $id=$_POST['id'];
        $sql = "UPDATE `staffvisiting` SET `onleave` = '1' WHERE `st_id` = $id";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Disable Visiting Staff " . $id;
        }
        else {
            echo "Error - Disable Visiting Staff " . $id;
            $logAction = "Error - Disable Visiting Staff " .  mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}

?>
