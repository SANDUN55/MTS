<?php
include 'database.php';
database_conectivity();
if(count($_POST)>0){
	if($_POST['type']==1){
       // print_r($_POST);
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
            $lab = $selectLab[0];
            $depStaffArray = $_POST['selectAcademicDep'];
            $staffDepts = explode('-',$depStaffArray);
            $dep = $staffDepts[0];
            $staff = $staffDepts[1];
            //mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
            $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `dep_code`, `staff`) 
                      VALUES ( $bno, '$module', '$activity', '$topic', $dep, $staff);";
            mysqli_query($conn, $sql);
            $submitID = mysqli_insert_id($conn);
            $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`) 
                      VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1);";
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
	if($_POST['type'] == 2){
		$id = $_POST['id'];
		$byr = $_POST['byr'];
		$bcode = $_POST['bcode'];
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
	if($_POST['type'] == 3){
		$id = $_POST['id'];
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
    if($_POST['type'] == 4){
        $id = $_POST['id'];
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
    if($_POST['type'] == 5){
        $id = $_POST['id'];
        $sql = "UPDATE batch SET `b_current_year`=(b_current_year+1)  WHERE b_no=$id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    if ($_POST['type'] == 6) {
        $bmod = explode('-', $_POST['selectBatchMo']);
        $bno = $bmod[0];
        $module = $bmod[1];
        $classId = $_POST['classList'];
        $classDt = $_POST['classDate'];
        $sql = "UPDATE  `classschedules` SET `class_start`= concat('$classDt ', time(`class_start`)), `class_end`= concat('$classDt ', time(`class_end`)) 
                    WHERE class_id = $classId ";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    if ($_POST['type'] == 7) {
        //batch=33&module=FOUN1+&bclass=22-36&classDate=2022-03-02&chkRes=1&type=7
        //class_id, '-', class_topic_id) as tids
        print_r($_POST);
        $chkval = 0;
        $batch = $_POST['batch'];
        $mod = $_POST['module'];
        $val = explode('-', $_POST['bclass']);
        $class = $val[0];
        $topic = $val[1];
        $chkval = $_POST['chkRes'];
        $scheduleDate = $_POST['classDate'];
        $lastClassDate = '';

        $sql_insetprev = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
                              SELECT $batch, '$mod', `activity`,`activity_no`,`class_topic`,`class_group`,`dep_code`,`staff` FROM `classtopics` WHERE  `topic_id` = $topic";

        if (mysqli_query($conn, $sql_insetprev))
        {
            $submitID = mysqli_insert_id($conn);
            //get the class date
            $getDate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATE(`class_start`) AS cdt FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class;"));
            $classDate = $getDate['cdt'];
            echo "Class date " . $classDate ."<br>";
            $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`,  `class_status`) 
                          SELECT $submitID, `class_start`,`class_end`,`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class";
            if (mysqli_query($conn, $sql2))
            {
                $submitedID2 = mysqli_insert_id($conn);
                $sql_updatedate = "UPDATE `classschedules`  SET  `class_start`= concat('$scheduleDate ', time(`class_start`)), `class_end`= concat('$scheduleDate ', time(`class_end`))
                                            WHERE `class_topic_id` = $submitID AND `class_id` = $submitedID2";
                //echo $sql_updatedate;
                if (mysqli_query($conn, $sql_updatedate)){
                    $err[$x] = 0;
                }else {
                    $err[$x] = 1;
                    $errStr .=  mysqli_error($conn);
                }
            }else {
                $err[$x] = 1;
                $errStr .=  mysqli_error($conn);
            }
            $lastClassDate = $classDate;
        }
        else {
            $err[$x] = 1;
            $errStr .=  mysqli_error($conn);
        }
        if($chkval == 1 )
        {
            $prev_batch = $batch - 1 ;
            $sql_getAllClass = "SELECT DATE(class_start) as classDate, CONCAT(class_id, '-', class_topic_id) as tids 
                                                                                  FROM classschedules s
                                                                                  JOIN classtopics t ON t.topic_id = s.class_topic_id
                                                                                  LEFT JOIN activity  ON activity = a_id
                                                                                  WHERE b_no = $prev_batch AND m_code = '$mod'
                                                                                  AND class_status = 1 AND   class_id > $class
                                                                                  ORDER BY class_start";
            //echo $sql_getAllClass;
            $result = mysqli_query($conn, $sql_getAllClass);
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $val = explode('-', $row['tids']);
                $class = $val[0];
                $topic = $val[1];
                $classDate = $row['classDate'];
                if($lastClassDate === $classDate)
                {
                    ////////
                    $sql_insetprev = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
                                      SELECT $batch, '$mod', `activity`,`activity_no`,`class_topic`,`class_group`,`dep_code`,`staff` FROM `classtopics` WHERE  `topic_id` = $topic";
                    if (mysqli_query($conn, $sql_insetprev)) {
                        $submitID = mysqli_insert_id($conn);
                        //get the class date
                        $getDate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATE(`class_start`) AS cdt FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class;"));
                        $classDate = $getDate['cdt'];
                        ////$sql_getprev = "SELECT `class_start`,`class_end`,`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = 27 AND `class_id` = 12 ";
                        $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`,  `class_status`) 
                                  SELECT $submitID, `class_start`,`class_end`,`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class";
                        if (mysqli_query($conn, $sql2)){
                            $submitedID2 = mysqli_insert_id($conn);
                            $sql_updatedate = "UPDATE `classschedules`  SET  `class_start`= concat('$scheduleDate ', time(`class_start`)), `class_end`= concat('$scheduleDate ', time(`class_end`))
                                                    WHERE `class_topic_id` = $submitID AND `class_id` = $submitedID2";
                            if (mysqli_query($conn, $sql_updatedate)){
                                $err[$x] = 0;
                            }else {
                                $err[$x] = 1;
                                $errStr .=  mysqli_error($conn);
                            }
                        }else {
                            $err[$x] = 1;
                            $errStr .=  mysqli_error($conn);
                        }
                        $lastClassDate = $classDate;
                    }
                    else {
                        $err[$x] = 1;
                        $errStr .=  mysqli_error($conn);
                    }
                    //////////
                }else {
                     $datetime1 = date_create($lastClassDate);
                    $datetime2 = date_create($classDate);
                    $interval = date_diff($datetime1, $datetime2);
                    $val =  $interval->format('%a');
                    $val1 = " + " . $val . " days";
                    $scheduleDate = date('Y-m-d', strtotime($scheduleDate. $val1));

                    ////////
                    $sql_insetprev = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
                                      SELECT $batch, '$mod', `activity`,`activity_no`,`class_topic`,`class_group`,`dep_code`,`staff` FROM `classtopics` WHERE  `topic_id` = $topic";
                    if (mysqli_query($conn, $sql_insetprev)) {
                        $submitID = mysqli_insert_id($conn);
                        //get the class date
                        $getDate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATE(`class_start`) AS cdt FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class;"));
                        $classDate = $getDate['cdt'];
                        ////$sql_getprev = "SELECT `class_start`,`class_end`,`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = 27 AND `class_id` = 12 ";
                        $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`,  `class_status`) 
                                  SELECT $submitID, `class_start`,`class_end`,`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class";
                        if (mysqli_query($conn, $sql2)){
                            $submitedID2 = mysqli_insert_id($conn);
                            $sql_updatedate = "UPDATE `classschedules`  SET  `class_start`= concat('$scheduleDate ', time(`class_start`)), `class_end`= concat('$scheduleDate ', time(`class_end`))
                                                    WHERE `class_topic_id` = $submitID AND `class_id` = $submitedID2";
                            if (mysqli_query($conn, $sql_updatedate)){
                                $err[$x] = 0;

                            }else {
                                $err[$x] = 1;
                                $errStr .=  mysqli_error($conn);
                            }
                        }else {
                            $err[$x] = 1;
                            $errStr .=  mysqli_error($conn);
                        }
                        $lastClassDate = $classDate;
                    }
                    else {
                        $err[$x] = 1;
                        $errStr .=  mysqli_error($conn);
                    }
                    //////////

                }

                if(in_array('1', $err) ){
                    echo "Error: "  . "<br>" . $sql . "<br>" . $errStr;
                }else{
                    echo json_encode(array("statusCode"=>200));
                }
                $i++;
            }//END WHILE
        }//END IF

        if(in_array('1', $err) ){
            echo "Error: "  . "<br>" . $sql . "<br>" . $errStr;
        }else{
            echo json_encode(array("statusCode"=>200));
        }


    }

}
?>
