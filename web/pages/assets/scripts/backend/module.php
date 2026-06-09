<?php
include 'database.php';
database_conectivity();
$sql = $logAction = '';
if(count($_POST) > 0){
	if($_POST['type'] == 1){
		$mc = strtoupper(trim($_POST['mcode']));
		$mn = trim($_POST['mname']);
		$mp = strtoupper(trim($_POST['selectPhase']));
		$ms = strtoupper(trim($_POST['selectStrand']));
		$mcgen = $mc.$mp;
		$sql = "INSERT INTO `module`( `m_code`, `m_name`, `m_phase`, `m_strand`) VALUES ('$mcgen', '$mn', $mp, $ms)";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode" => 200));
            $logAction = "Success - Add Module " . $mcgen . $mn . $mp . $ms;
		}
        else {
            echo "Error -  Add Module ";
            $logAction = "Error -  Add Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST) > 0){
	if($_POST['type'] == 2){
		$id = $_POST['id'];
		$code = $_POST['mcode'];
		$name = $_POST['mname'];
		$phase = $_POST['selectPhase'];
        $strand = $_POST['selectStrand'];
		$sql = "UPDATE `module` SET `m_code` = '$code', `m_name` = '$name', `m_phase` = $phase, `m_strand` = $strand WHERE m_code = '$id'";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode" => 200));
            $logAction = "Success - Edit Module " . $code . $name . $phase . $strand . $id;
		}
        else {
            echo "Error -  Add Module ";
            $logAction = "Error - Edit Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST) > 0){
	if($_POST['type'] == 3){
		$id = $_POST['id'];
		$sql = "DELETE FROM `module` WHERE m_code = '$id' ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
            $logAction = "Success - Delete Module " . $id;
		}
        else {
            echo "Error -  Add Module";
            $logAction = "Error - Delete Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST) > 0){
	if($_POST['type'] == 4){
		$id = $_POST['id'];
		$sql = "UPDATE `module` SET `m_st` = 0,  m_st_change_on = now() WHERE m_code = '$id'";
		if (mysqli_query($conn, $sql)) {
			echo $id;
            $logAction = "Success - Disable Module " . $id;
		}
        else {
            echo "Error - Disable Module";
            $logAction = "Error - Disable Module " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
?>
