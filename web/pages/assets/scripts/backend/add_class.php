<?php
// Start output buffering to catch any accidental output
ob_start();

// Set error handling for AJAX requests
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Set JSON header for AJAX responses
header('Content-Type: application/json');

include 'database.php';
database_conectivity();
$sql = $log_text = '';
if(count($_POST)>0){
	if($_POST['type']==1) {
        //print_r($_POST);
        $bmod = explode('-', $_POST['selectBatchMo']);
        $bno = $bmod[0];
        $module = trim($bmod[1]);
        $activity = $_POST['selectActivity'];
        $topic = addslashes(trim($_POST['classTopic']));
        $classDate = $_POST['classDate'];
        $classStTime = $_POST['classStTime'];
        $classEnTime = $_POST['classEnTime'];
        $classStTime = $classDate . ' ' . $classStTime;
        $classEnTime = $classDate . ' ' . $classEnTime;
        //echo $classStTime; echo $classEnTime;
        $labArray = $_POST['selectLab'];
        $lab = $labArray[0];
      $selectedLecturers = $_POST['selectedLecturers'] ?? array();
        if (!is_array($selectedLecturers)) {
            $selectedLecturers = array($selectedLecturers);
        }
        $depStaffArray = $selectedLecturers[0] ?? ($_POST['selectAcademicDep'] ?? ($_POST['selectAcademicDep1'] ?? ''));
        if (is_array($depStaffArray)) {
            $depStaffArray = $depStaffArray[0] ?? '';
        }
        if ($depStaffArray === '') {
            http_response_code(400);
            echo json_encode(array("statusCode"=>400, "message"=>"Please select a lecturer."));
            exit;
        }
        $staffDepts = explode('-',$depStaffArray);
        if (count($staffDepts) < 2) {
            http_response_code(400);
            echo json_encode(array("statusCode"=>400, "message"=>"Invalid lecturer selection."));
            exit;
        }
        $dep = $staffDepts[0];
        $staff = $staffDepts[1];
        $addStaff = $_POST["staffID"];
        $onlineclass = $_POST["onlinec"];
        if($onlineclass<>'' && $onlineclass==0) $lab=0;
        $classremark = addslashes(trim($_POST["remark"]));
        mysqli_query($conn,'LOCK TABLES classtopics WRITE, classtopics_new WRITE, classtopics_staff WRITE, classschedules WRITE;');
        $sql = "INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff) 
VALUES ( $bno, '$module', '$activity', '$topic', 'All', $dep, $staff);";
        //echo $sql;
        if (!mysqli_query($conn, $sql)) {
            mysqli_query($conn,'UNLOCK TABLES;');
            http_response_code(400);
            echo json_encode(array("statusCode"=>400, "message"=>"Error - Add class topic: " . mysqli_error($conn)));
            exit;
        }
        $submitID = mysqli_insert_id($conn);
        /////////////////////////////////////////////////////////////////////////////////////////
        $sqlNew = "INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
           VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);";
            mysqli_query($conn, $sqlNew);

            // Insert selected lecturers into classtopics_staff table.
         $selectedLecturers = array_unique(array_filter($selectedLecturers));
            if (empty($selectedLecturers)) {
                $selectedLecturers = array($depStaffArray);
            }
            foreach ($selectedLecturers as $lecturerValue) {
                $lecturerParts = explode('-', $lecturerValue);
                if (count($lecturerParts) < 2) {
                    continue;
                }
                $lecturerStaff = $lecturerParts[1];
                $sqlStaff = "INSERT INTO classtopics_staff (topic_id, class_group, staff) 
                            VALUES ($submitID, 'All', $lecturerStaff);";
                mysqli_query($conn, $sqlStaff);
            }
        /////////////////////////////////////////////////////////////////////////////////////////

        $sql = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark ) 
VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');";
        //echo $sql;
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn,'UNLOCK TABLES;');
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Class " . $submitID;
        }
        else {
            mysqli_query($conn,'UNLOCK TABLES;');
            echo "Error -  Add Class  ";
            $logAction = "Error -  Add Class " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }

}
if(count($_POST)>0){
	if($_POST['type'] == 2){
		$id = $_POST['id'];
		$byr = $_POST['byr'];
		$bcode = $_POST['bcode'];
		$sql = "UPDATE batch SET b_yr='$byr', b_code='$bcode' WHERE b_no=$id";
		if (mysqli_query($conn, $sql)) {
			http_response_code(200);
			header('Content-Type: application/json');
			echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Update batch " . $byr . $bcode;
		} 
		else {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>400, "message"=>"Error - Update batch"));
            $logAction = "Error - Update batch " . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type'] == 3){
		$id = $_POST['id'];
		$sql = "DELETE FROM batch WHERE b_no=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
            $logAction = "Success - Delete batch " . $id;
		} 
		else {
			echo "Error - Delete batch ";
            $logAction = "Error - Update batch " . mysqli_error($conn);
		}
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
    if($_POST['type'] == 4){
        $id = $_POST['id'];
        $sql = "UPDATE batch SET batchstatus= 0 WHERE b_no = $id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Update batch disable" . $id;
        }
        else {
            echo "Error - Update batch disable ";
            $logAction = "Error - Update batch disable " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type'] == 5){
        $id = $_POST['id'];
        $sql = "UPDATE batch SET b_current_year=(b_current_year+1)  WHERE b_no=$id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
            $logAction = "Success - Upgrade batch " . $id;
        }
        else {
            echo "Error - Upgrade batch ";
            $logAction = "Error - Upgrade batch" . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
    if ($_POST['type'] == 6) {
       //print_r($_POST);
        $classTopics = explode('-', $_POST['classList']);
        $classId = $classTopics[0];
        $classDt = $_POST['classDate'];
        $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
        $rc1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT res_code FROM classschedules WHERE class_id = $classId"));
        $rescode = $rc1['res_code'];
       $sql_updatedate = "UPDATE classschedules  SET  class_start= concat('$classDt ', time(class_start)), class_end= concat('$classDt ', time(class_end)), res_code=NULL 
                                                    WHERE  class_id = $classId";
        if($rescode>0){
            $usrnm = $_SESSION['userMtsFom'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $cancel_lab = "UPDATE reserve SET res_st=0, st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
            mysqli_query($labconn, $cancel_lab );
        }
        if (mysqli_query($conn, $sql_updatedate)) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Reschedule class " . $classId;
        }
        else {
            echo "Error - Reschedule class ";
            $logAction = "Error - Reschedule class " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
    if ($_POST['type'] == 78) {
        print_r($_POST);
       // //$data = explode('&', $_POST['data']);
        $currentBatch  = explode('-', $_POST['selectBatchMo']);
        $curBatch = $currentBatch['0'];
        $batch = $_POST['batch'];
        $module = $_POST['module'];
        $bm = explode('-', $_POST['bclass']);
        $classID = $bm[0];
        $topicID = $bm[1];
        $str = explode('=', $data[2]);
        $resDt = $_POST['classDate'];
        $get_classIds = "SELECT class_id, res_code FROM classtopics inner 
                JOIN classschedules on class_topic_id = topic_id 
                WHERE b_no= $batch AND m_code= '$module' 
                AND DATE(class_start) IN (SELECT DATE(sc.class_start) AS cdt FROM classschedules sc WHERE sc.class_topic_id = $topicID AND sc.class_id = $classID)";
echo $get_classIds;
        $result = mysqli_query($conn, $get_classIds);
        $updateSql = '';
        $usrnm = $_SESSION['userMtsFom'];
        $ip = $_SERVER['REMOTE_ADDR'];
     /*   while($row = mysqli_fetch_assoc($result)){
            $classId = $row['class_id'];
            $rescode = $row['res_code'];

      $insertSql = "INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff)
                SELECT $curBatch, '$module', activity, class_topic, class_group, dep_code, staff FROM classtopics WHERE  topic_id` = $topicID
        echo $insertSql;
        //mysqli_query($conn, $sql);
        $submitID = mysqli_insert_id($conn);
        $sql = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark )
VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');";



            $insertTopic = "INSERT INTO classtopics(topic_id, b_no, m_code, activity, activity_no, class_topic, class_group, dep_code, staff)
                            VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]')"

            $updateSql .= "UPDATE classschedules  SET  class_start= concat('$resDt ', time(class_start)), class_end= concat('$resDt ', time(class_end)), res_code = NULL WHERE  class_id = $classId ; ";
            if($rescode>0){
                $cancel_lab = "UPDATE reserve SET res_st=0, st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
                mysqli_query($labconn, $cancel_lab );
            }
        }
        if (mysqli_multi_query($conn, $updateSql)) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Reschedule day " . $classId;
        }
        else {
            echo "Error - Reschedule day ";
            $logAction = "Error - Reschedule day " . mysqli_error($conn);
        }*/
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
    if ($_POST['type'] == 7) {
        //batch=33&module=FOUN1+&bclass=22-36&classDate=2022-03-02&chkRes=1&type=7
        print_r($_POST);
        $currentBatch  = explode('-', $_POST['selectBatchMo']);
        $batch = $currentBatch['0'];
        //$batch = $_POST['batch']; prv batch
        $mod = $currentBatch['1'];
        $scheduleDate = $_POST['classDate'];
        //
       // $newBatch = '32';
        $prvBatch = $batch - 1;
        //$mod = 'PDFM2';$scheduleDate = '2024-02-22';
        $sql_getDates = "SELECT DISTINCT(DATE(class_start)) AS dts , GROUP_CONCAT(topic_id) AS tps 
                    FROM classschedules 
                    JOIN classtopics ON class_topic_id =  classtopics.topic_id 
                    JOIN activity ON activity.a_id = classtopics.activity
                    WHERE classtopics.b_no = $prvBatch AND classtopics.m_code = '$mod'
                    AND activity.a_type = 'N'
                    GROUP BY dts;";
echo $sql_getDates;
        $res_getDates = mysqli_query($conn, $sql_getDates);
        $prvBatch_dates = array(); $prvBatch_dates_topics = array(); $a = 0;
        while($row1 = mysqli_fetch_assoc($res_getDates)){
            $prvBatch_dates[$a] = $row1['dts'];
            $prvBatch_dates_topics[$a] = $row1['tps'];
            $a++;
        }
        echo "<pre>";
        print_r($prvBatch_dates);
        print_r($prvBatch_dates_topics);
        echo "/<pre>";
        $arrayCount = count($prvBatch_dates);
        $arrayCount2 = count($prvBatch_dates);
        echo "<br>";
        echo $arrayCount; echo "<br>";
        $newDates = array(); $x=0;
        for($y=0;$y<$arrayCount;$y++) {
            $val1 = " + " . $x . " days";
            //echo $val1; echo "-";
            $minDate = date('Y-m-d', strtotime($scheduleDt. $val1));
            //echo $minDate;
            // echo "--";
            $dayOfWeek = date('w', strtotime($minDate));
            if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
                $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM holidays WHERE ('$minDate' BETWEEN startDt AND enddt)"));
                $chkHoliday = $getHoliday['ct'];
                // echo $chkHoliday ;
                if($chkHoliday==0){
                    $newDates[$y]=$minDate;
                }else{
                    ++$arrayCount;
                }
            }else{
                ++$arrayCount;
            }
            // echo "<br>";
            ++$x;
        }
        $newDates = array_values($newDates);
        echo "<pre>";
        print_r($newDates);
        print_r($prvBatch_dates_topics);
        echo "</pre>";
        echo count($newDates);

        for($x=0; $x<$arrayCount2; $x++){
            $prvTopics_arr = explode(',', $prvBatch_dates_topics[$x]);
            $prvTopics_arr_count = count($prvTopics_arr);
            $classDate = $newDates[$x];
            echo $newDates[$x];
            for($y=0;$y<$prvTopics_arr_count;$y++){
                $topic = $prvTopics_arr[$y];
                $sqlInTopicNewBatch =  "INSERT INTO classtopics (b_no, m_code, activity, activity_no, class_topic, class_group, dep_code, staff) 
												  SELECT $newBatch, '$mod', activity,activity_no,class_topic,class_group,dep_code,staff 
												  FROM classtopics WHERE  topic_id = $topic";
                echo $sqlInTopicNewBatch; echo "<br>";
                $submitID = mysqli_insert_id($conn);
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////


                ////////////////////////////////////////////////////////////////////////////////////////////////////////////



                $sqlInsertSchedule = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code,  class_status) 
                                  SELECT $submitID, concat('$classDate ', time(class_start)), concat('$classDate  ', time(class_end)),lab_code,class_status 
                                  FROM classschedules 
                                  WHERE  class_topic_id = $topic";
                echo $sqlInsertSchedule; echo "<br>";
            }
        }




    }

   //ADD GROUP CLASS
    if($_POST['type']==8){
        // Clear any output buffers to ensure clean JSON response
        ob_clean();
        
        try {
            //print_r($_POST);
            $bmod = explode('-', $_POST['selectBatchMo']);
            $bno = $bmod[0];
            $module = trim($bmod[1]);
            $activity = $_POST['selectActivity'];
            $topic = addslashes(trim($_POST['classTopic']));
            $activityNo = $_POST['activity-no'];
            extract($_POST);
            $err = array();
            $errStr = '';
            mysqli_query($conn,'LOCK TABLES classtopics WRITE, classschedules WRITE, classtopics_new WRITE, classtopics_staff WRITE;');
                $groupCount  = $_POST['group-count'];

                $w=fopen("group.txt","w+");
               
                for($x = 0 ; $x < $groupCount ; $x++) {
                    $groupN = $activityGroup[$x];
                    fwrite($w,"$groupN\n");
                    
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
                    $sql = "INSERT INTO classtopics (b_no, m_code, activity, activity_no, class_topic, class_group, dep_code, staff) 
                                                      VALUES ( $bno, '$module', '$activity', $activityNo,  '$topic', '$groupN', $dep, $staff);";
                    $sqlstr .= $sql;
                    if (mysqli_query($conn, $sql)) {
                        $submitID = mysqli_insert_id($conn);
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $sqlNew = "INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
                         VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);";
                        mysqli_query($conn, $sqlNew);

                        // Insert into classtopics_staff table
                        $sqlStaff = "INSERT INTO classtopics_staff (topic_id, class_group, staff) 
                                    VALUES ($submitID, '$groupN', $staff);";
                        mysqli_query($conn, $sqlStaff);
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        $sql2 = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code,  class_status) VALUES ($submitID, '$StTime', '$EnTime', $lab,  1);";

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
                   // echo "<br>". $sql ."<br>". $sql2 . '-----';
                }
                fclose($w);
                //echo  in_array('1', $err);
                //print_r($err);
                //echo ($errStr);
            mysqli_query($conn,'UNLOCK TABLES classtopics;');
                if(in_array('1', $err) ){
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(array("statusCode"=>400, "message"=>"Error - Add group class " . mysqli_error($conn)));
                    $logAction = "Error - Add group class " . mysqli_error($conn);
                }else{
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(array("statusCode"=>200));
                    $logAction = "Success - Add group class ";
                }
            $logAction = addslashes($logAction);
            writeLog($logAction);
            mysqli_close($conn);
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>400, "message"=>"Error: " . $e->getMessage()));
            $logAction = "Error - Add group class " . $e->getMessage();
            $logAction = addslashes($logAction);
            writeLog($logAction);
        }
        ob_end_flush();
        exit;
    }
    //CLASS REQUEST APPROVE   //REJECT REQUEST APPROVE
    if($_POST['type']==9) {
        $cid = $_POST['classId'];
        $tid = $_POST['topicId'];
        $phpStTime = $_POST['classSt'];
        $classStTime = date('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['classEn'];
        $classEnTime = date('Y-m-d H:i', strtotime($phpEnTime));
        $editStaff = $_POST["userId"];
        $rid = $_POST['reqId'];
        $sql = "UPDATE classschedules SET  class_start = '$classStTime', class_end = '$classEnTime', edit_staff = '$editStaff', edit_dt = now(), res_code = NULL   
              WHERE class_id = $cid AND class_topic_id = $tid ;";
        $sql .= "UPDATE classsreq SET req_status= 2,edit_staff='$editStaff', edit_dt= now() WHERE req_id = $rid ; ";
        //echo $sql;
        $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
        $usrnm = $_SESSION['userMtsFom'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $cancel_lab = "UPDATE reserve SET res_st=0,  st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
        mysqli_query($labconn, $cancel_lab );
        if (mysqli_multi_query($conn, $sql)) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Approve class change request " . $rid;
            $steml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em FROM classtopics  JOIN staff ON st_id = staff WHERE topic_id = $tid ;"));
            $seml = $steml['st_em'];
            $to = $seml ;
            $subject = "Batch " . $batch . $modnm . " request change APPROVED- MTS, Faculty of Medicine";
            $message = "Hi, <br>
Your requested change to the timetable is incorporated. <br><br>
Module Convener<br><br>
This is an automatically generated email <br>";
            $headers = 'From: "Module Timetable System" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f gayathri@kln.ac.lk");
        }
        else {
            echo "Error - Approve class change request ";
            $logAction = "Error - Approve class change request " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
    if($_POST['type']==10) {
        $editStaff = $_POST["userId"];
        $rid = $_POST['reqId'];
        $sql = "UPDATE classsreq SET req_status= 1,edit_staff='$editStaff', edit_dt= now() WHERE req_id = $rid ; ";
        //echo $sql;
        if (mysqli_query($conn, $sql)) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Reject class change request " . $rid;
            $steml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_em from staff WHERE st_id IN (SELECT staff FROM classsreq JOIN classtopics ON topic_id = class_topic_id WHERE req_id = $rid);"));
            $seml = $steml['st_em'];
            $to = $seml ;
            $subject = "Request time change REJECT- Module Timetable System";
            $message = "Hi,<br><br>
I am sorry, the change you are requested can not be accommodated.<br><br>
Module Convener<br><br>
This is an automatically generated email.<br>";
            $headers = 'From: "Module Timetable System" <medu@kln.ac.lk>'. "\r\n" ;
            $headers .= "Content-Type: text/html;";
            mail($to, $subject, $message, $headers,"-f gayathri@kln.ac.lk");
        }
        else {
            echo "Error - Reject class change request ";
            $logAction = "Error - Reject class change request " . mysqli_error($conn);
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);
    }
    if($_POST['type']==11) {
       // print_r($_POST);
       $bmod = explode('-', $_POST['selectBatchMo']);
        $bno = $bmod[0];
        $module = trim($bmod[1]);
        $activity = $_POST['selectActivity'];
        $topic = addslashes(trim($_POST['classTopic']));
        $classStDate = $_POST['classStDate'];
        $classEnDate = $_POST['classEnDate'];
        $classStTime = $_POST['classStTime'];
        $classEnTime = $_POST['classEnTime'];
        $depStaffArray = $_POST['selectAcademicDep'];
        $lab = $_POST['selectLab'];
        $staffDepts = explode('-',$depStaffArray);
        $dep = $staffDepts[0];
        $staff = $staffDepts[1];
        $datetime1 = date_create($classStDate);
        $datetime2 = date_create($classEnDate);
        $interval = date_diff($datetime1, $datetime2);
        $val =  $interval->format('%a');
        $err = array();
        $addStaff = $_POST["staffID"];
        for($x=0;$x<=$val;$x++){
            $val1 = " + " . $x . " days";
            $chkHoliday='';$date='';
           // echo date('Y-m-d', strtotime($classStDate. $val1));
            $date = date('Y-m-d', strtotime($classStDate. $val1));
            $dayOfWeek = date('w', strtotime($date));
            if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
                //echo "SELECT COUNT(*) AS ct FROM holidays WHERE ('$date' BETWEEN startDt AND enddt)";
                $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM holidays WHERE ('$date' BETWEEN startDt AND enddt)"));
                $chkHoliday = $getHoliday['ct'];
                if($chkHoliday==0){
                   // echo 'The date is weekday';
                    $classStTime1 = $date . ' ' . $classStTime;
                    $classEnTime1 = $date . ' ' . $classEnTime;
                    $submitID=''; $sql='';
                    mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
                    $sql = "INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff) 
                              VALUES ( $bno, '$module', '$activity', '$topic', 'A', $dep, $staff);";
                    //echo $sql; echo "<br>";
                    mysqli_query($conn, $sql);
                    $submitID = mysqli_insert_id($conn);
                    /////////////////////////////////////////////////////////////////////////////////////////
                    $sqlNew = "INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
                          VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);";
                        mysqli_query($conn, $sqlNew);

                        // Insert into classtopics_staff table
                        $sqlStaff = "INSERT INTO classtopics_staff (topic_id, class_group, staff) 
                                    VALUES ($submitID, 'A', $staff);";
                        mysqli_query($conn, $sqlStaff);
                    /////////////////////////////////////////////////////////////////////////////////////////
                    $sql2 = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark ) 
                                VALUES ($submitID, '$classStTime1', '$classEnTime1', $lab, 1, '$addStaff', '$classremark');";
                    //echo $sql; echo "<br>";
                    if(mysqli_query($conn, $sql2)) {
                        $err[$x] = 0;
                        //echo json_encode(array("statusCode"=>200));
                        //$logAction = "Success - Add Class " . $submitID;
                    }
                    else {
                        $err[$x] = 1;
                        $errStr .=  mysqli_error($conn);
                        //echo "Error -  Add Class  ";
                        //$logAction = "Error -  Add Class " . mysqli_error($conn);
                    }
                    mysqli_query($conn,'UNLOCK TABLES;');
                    }
                }
            }
        if(in_array('1', $err) ){
            echo "Error - Add Clinical Timetable ";
            $logAction = "Error - Add Clinical Timetable " . mysqli_error($conn);
        }else{
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("statusCode"=>200));
            $logAction = "Success - Add Clinical Timetable ";
        }
        $logAction = addslashes($logAction);
        writeLog($logAction);
        mysqli_close($conn);

    }
}
?>
