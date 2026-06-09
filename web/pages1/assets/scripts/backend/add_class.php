<?php
include 'database.php';
database_conectivity();

// ====================== NEW: LOAD CLASS DATA FOR EDIT (Type 11) ======================
if (isset($_POST['type']) && $_POST['type'] == 11) {
    $id1 = $_POST['id'];
    $parts = explode('-', $id1);
    $classId  = intval($parts[0] ?? 0);
    $topicId  = intval($parts[1] ?? 0);

    if ($classId == 0 || $topicId == 0) {
        echo json_encode(["statusCode" => 400, "message" => "Invalid ID"]);
        exit;
    }

    $sql = "
        SELECT 
            c.class_id,
            c.class_topic_id,
            c.class_start,
            c.class_end,
            c.lab_code,
            c.class_remark,
            ct.b_no,
            ct.m_code,
            ct.activity,
            ct.class_topic,
            ct.class_group,
            GROUP_CONCAT(CONCAT(cs.dep_code, '-', cs.staff) SEPARATOR ',') as lecturers
        FROM classschedules c
        JOIN classtopics ct ON c.class_topic_id = ct.topic_id
        LEFT JOIN classtopics_staff cs ON ct.topic_id = cs.topic_id
        WHERE c.class_id = $classId 
          AND c.class_topic_id = $topicId
        GROUP BY c.class_id";

    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $data = [
            "class_id"    => $row['class_id'],
            "topic_id"    => $row['class_topic_id'],
            "date"        => date('Y-m-d', strtotime($row['class_start'])),
            "startTime"   => date('H:i', strtotime($row['class_start'])),
            "endTime"     => date('H:i', strtotime($row['class_end'])),
            "lab_code"    => $row['lab_code'],
            "remark"      => $row['class_remark'],
            "activity"    => $row['activity'],
            "class_topic" => $row['class_topic'],
            "class_group" => $row['class_group'],
            "lecturers"   => $row['lecturers'] ? explode(',', $row['lecturers']) : []
        ];
        echo json_encode(["statusCode" => 200, "data" => $data]);
    } else {
        echo json_encode(["statusCode" => 404, "message" => "Class not found"]);
    }
    exit;
}
//=======================================================================
if(count($_POST)>0){
	if($_POST['type']==1){
        $bmod = explode('-', $_POST['selectBatchMo']);
        $bno = $bmod[0];
        $module = $bmod[1];
        $activity = $_POST['selectActivity'];
        $topic = addslashes(trim($_POST['classTopic']));
        $activityNo = $_POST['activity-no'];
        extract($_POST);
        $err = array();
        $errStr = '';
        mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
        if($_POST['classType'] == 'G'){
            $groupCount  = $_POST['group-count'];
            for($x = 0 ; $x < $groupCount ; $x++) {
                $groupN = $activityGroup[$x];
                $StTime = $activityDate[$x].' '.$activityStTime[$x];
                $EnTime = $activityDate[$x].' '.$activityEnTime[$x];
                $lab = $selectLab[$x];
               // echo 'lab'.$lab;
                if(!$lab) $lab = 0;
              // echo 'group'.$x.$groupN.'---';

                $depStaffArray = $selectAcademicDepVal[$x];
                $staffDepts = explode('-', $depStaffArray);
                //print_r($staffDepts);
                $dep=$staffDepts[0];
                $staff=$staffDepts[1];
                $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
                                                  VALUES ( $bno, '$module', '$activity', $activityNo,  '$topic', '$groupN', $dep, $staff);";
                $sqlstr .= $sql;
                if (mysqli_query($conn, $sql)) {
                    $submitID = mysqli_insert_id($conn);
                    $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`,  `class_status`) VALUES ($submitID, '$StTime', '$EnTime', $lab,  1);";

                    if (mysqli_query($conn, $sql2)){
                        $err[$x] = 0;
                    }else {
                        $err[$x] = 1;
                        $errStr .=  mysqli_error($conn);
                    }
                    //echo json_encode(array("statusCode"=>200));
                } else {
                    $err[$x] = 1;
                    $errStr .=  mysqli_error($conn);
                }
               //echo "<br>". $sql ."<br>". $sql2 . '-----';
            }
            //echo  in_array('1', $err);
            //print_r($err);
            //echo ($errStr);
            if(in_array('1', $err) ){
                echo "Error: "  . "<br>" . $sql . "<br>" . $errStr;
            }else{
                echo json_encode(array("statusCode"=>200));
            }
        }elseif ($_POST['classType'] == 'N'){
            $classStTime = $classDate . ' ' . $classStTime;
            $classEnTime = $classDate . ' ' . $classEnTime;
            $lab = $selectLab[1];
            $depStaffArray=$_POST['selectAcademicDep'];
            $staffDepts=explode('-',$depStaffArray);
            $dep=$staffDepts[0];
            $staff=$staffDepts[1];
            //mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
            $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `dep_code`, `staff`) VALUES ( $bno, '$module', '$activity', '$topic', $dep, $staff);";
            mysqli_query($conn, $sql);
            $submitID=mysqli_insert_id($conn);
            $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`) VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1);";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            }
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
           // mysqli_query($conn,'UNLOCK TABLES classtopic;');
        }
        mysqli_query($conn,'UNLOCK TABLES classtopic;');
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
        //echo $id;
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
