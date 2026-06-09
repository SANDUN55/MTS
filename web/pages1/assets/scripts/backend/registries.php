<?php
include 'database.php';
database_conectivity();
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
            $logAction = "Add Holiday success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Add Holiday error " . addslashes($sql);
		}
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
            $logAction = "Edit Holiday success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Edit Holiday error " . addslashes($sql);
		}
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
            $logAction = "Delete Holiday success " . addslashes($sql);
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Delete Holiday error " . addslashes($sql);
		}
        writeLog($logAction);
		mysqli_close($conn);
	}
}
//VISITING STAFF REGISTRY
if(count($_POST)>0){
    if($_POST['type']==5){
        $title = $_POST['title'];
        $fname = trim($_POST['fname']);
        $sname = trim($_POST['sname']);
        $email = trim($_POST['eml']);
        $staffDBcon = mysqli_connect('localhost', 'gayathri', '1234@Gayathri' , 'staffdb') or die (mysqli_error());
        //echo mysqli_error($staffDBcon);
        $sql = "INSERT INTO `staff`( `title_id`, `firstname`, `surname`, `st_em`, `desig_id`, `dep_code`, `st_cat`, `st_sts` ) VALUES ( $title, '$fname', '$sname', '$email', 25, 24, 4, 1)";
        if (mysqli_query($staffDBcon, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Add Visiting Staff " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction="Add Visiting Staff error " . mysqli_error($conn);
        }
        mysqli_close($staffDBcon);
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
        $staffDBcon = mysqli_connect('localhost', 'root', '' , 'staffdb') or die (mysqli_error());
        //echo mysqli_error($staffDBcon);
        $sql = "UPDATE `staff` SET `title_id` = $title, `firstname` = '$fname', `surname` = '$sname', `st_em` = '$email' WHERE `st_id` = $id";
        if (mysqli_query($staffDBcon, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "Edit Visiting Staff " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "Edit Visiting Staff error " . mysqli_error($conn);
        }
        mysqli_close($staffDBcon);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==7){
        $id=$_POST['id'];
        $staffDBcon = mysqli_connect('localhost', 'root', '' , 'staffdb') or die (mysqli_error());
        $sql = "UPDATE `staff` SET `st_sts` = 0 WHERE `st_id` = $id";
        if (mysqli_query($staffDBcon, $sql)) {
            echo $id;
            $logAction = "Disable Visiting Staff " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($staffDBcon);
            $logAction = "Disable Visiting Staff error " . addslashes($sql);
        }
        mysqli_close($staffDBcon);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
?>
