<?php
include 'database.php';
database_conectivity();
session_start();
$labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
// if(count($_POST)>0){
// 	if($_POST['type']==1){
// 	    //print_r($_POST);
//         $bno = $_POST['getBatch'];
//         $module = $_POST['getMod'];
//         $activity = $_POST['selectActivity'];
//         $topic = addslashes(trim($_POST['classTopic']));
//         $phpStTime = $_POST['classStTime'];
//         $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
//         $phpEnTime = $_POST['classEnTime'];
//         $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
//         $labArray = $_POST['selectLab'];
//         $lab = $labArray[0];
//        // echo $lab;
//        // echo "---";
//         $depStaffArray = $_POST['selectAcademicDep'];
//         $staffDepts = explode('-',$depStaffArray);
//         $dep = $staffDepts[0];
//         $staff = $staffDepts[1];
//         $addStaff = $_SESSION["userMtsFom"];
//         $onlineclass = $_POST["onlinec"];
//        // echo $onlineclass;
//        // echo "---";
//         if($onlineclass<>'' && $onlineclass==0) $lab=0;
// //echo $lab;
//         $classremark = addslashes(trim($_POST["remark"]));
//         mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
//         $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `class_group`, `dep_code`, `staff`) 
// VALUES ( $bno, '$module', '$activity', '$topic', 'A', $dep, $staff);";
//         mysqli_query($conn, $sql);
//         $submitID=mysqli_insert_id($conn);
//         $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`, `add_staff`, `class_remark` ) 
// VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');";
//         //echo $sql;
//         if (mysqli_query($conn, $sql)) {
//             echo json_encode(array("statusCode"=>200));
//         }
//         else {
//             echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//         }
//         mysqli_query($conn,'UNLOCK TABLES classtopic;');
//         mysqli_close($conn);
// 	}
// }

if (count($_POST) > 0) {
    
    if ($_POST['type'] == 1) {
        database_conectivity();
        global $conn;

        $bno = $_POST['getBatch'];
        $module = $_POST['getMod'];
        $activity = $_POST['selectActivity'];
        $topic = addslashes(trim($_POST['classTopic']));
        $phpStTime = $_POST['classStTime'];
        $classStTime = date('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['classEnTime'];
        $classEnTime = date('Y-m-d H:i', strtotime($phpEnTime));
        $labArray = $_POST['selectLab'];
        $lab = isset($labArray[0]) ? $labArray[0] : 0;  // safer

        $depStaffArray = isset($_POST['selectedLecturers']) ? $_POST['selectedLecturers'] : []; // make sure it's set and is array
        
    

        $addStaff = isset($_SESSION["userMtsFom"]) ? $_SESSION["userMtsFom"] : 'unknown';

        // Check if 'onlinec' is set
        $onlineclass = isset($_POST["onlinec"]) ? $_POST["onlinec"] : '';

        $classremark = addslashes(trim(isset($_POST["remark"]) ? $_POST["remark"] : ''));
        
        if ($onlineclass !== '' && $onlineclass == 0) $lab = 0;

        // Validate $depStaffArray has at least one item
        $val = !is_array($depStaffArray);


          
        if (empty($depStaffArray) || !is_array($depStaffArray)) {
            echo json_encode(["statusCode" => 400, "message" => "No academic department/staff data received"]);
            exit;
        }
   
        // Lock tables
        mysqli_query($conn, 'LOCK TABLES classtopics_new WRITE, classtopics_staff WRITE, classtopics WRITE, classschedules WRITE;');

        // Safely explode the first staff info
        $firstStaff = explode('-', $depStaffArray[0]);
        if (count($firstStaff) < 2) {
            echo json_encode(["statusCode" => 400, "message" => "Invalid staff data format"]);
            mysqli_query($conn, 'UNLOCK TABLES;');
            exit;
        }
 
        $dep = intval($firstStaff[0]);
        $staff = intval($firstStaff[1]);
        $submitID = mysqli_insert_id($conn); // get new topic_id
  // Insert into old classtopics with first lecturer only
        $insertOldTopic = "INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff)
                           VALUES ($bno, '$module', '$activity', '$topic', 'ALL', $dep, $staff);";

        mysqli_query($conn, $insertOldTopic);
        $submit = "SELECT topic_id FROM classtopics WHERE b_no = $bno AND m_code = '$module' AND activity = $activity AND class_topic = '$topic' AND dep_code = $dep AND staff = $staff";
        $submitID = mysqli_fetch_assoc(mysqli_query($conn, $submit))['topic_id'];
        // Insert into classschedules
        $insertSchedule = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark)
                           VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');";
        
        
        if (mysqli_query($conn, $insertSchedule)) {
            echo json_encode(array("statusCode" => 200));
        } else {
            echo "Error: " . $insertSchedule . "<br>" . mysqli_error($conn);
        }


        
        // Insert into classtopics_new
        $insertTopic = "INSERT INTO classtopics_new (topic_id,b_no, m_code, activity, activity_no, class_topic, dep_code)
                        VALUES ($submitID,$bno, '$module', '$activity', 1, '$topic', $dep);";

       mysqli_query($conn, $insertTopic);
       

        // Insert into classtopics_staff for each lecturer
        foreach ($depStaffArray as $staffDepCombo) {
                $parts = explode('-', $staffDepCombo);
                if (count($parts) < 2) {
                    // skip invalid entries
                    continue;
                }
                $depCode = intval($parts[0]);
                $staffID = intval($parts[1]);

                $insertStaff = "INSERT INTO classtopics_staff (topic_id, class_group, staff)
                                VALUES ($submitID, 'A', $staffID);";

                        
                mysqli_query($conn, $insertStaff);
            $file = fopen("log7.txt", "w+");
                        fwrite($file, __LINE__ . "\n");
                        fwrite($file, $insertStaff);
                        fclose($file);
            }


        

        // Unlock tables
        mysqli_query($conn, 'UNLOCK TABLES;');
        mysqli_close($conn);
    }
}
// if (count($_POST) > 0) {
//     if ($_POST['type'] == 2) {
//         // Extract posted data
//         $id1 = $_POST['classReserveID'];
//         $id2 = explode('-', $id1);
//         $classId = $id2[0];
//         $topicId = $id2[1];
//         $activity = $_POST['selectActivity'];
//         $topic = addslashes($_POST['classTopicVal']);
//         $group = addslashes($_POST['classGroupVal']);
//         $phpStTime = $_POST['classStTime'];
//         $classStTime = date('Y-m-d H:i', strtotime($phpStTime));
//         $phpEnTime = $_POST['classEnTime'];
//         $classEnTime = date('Y-m-d H:i', strtotime($phpEnTime));
//         $selectLab = $_POST['selectLab'];
//         $lab = $selectLab[0];
//         $onlineclass = $_POST["onlinec"];
//         if ($onlineclass !== '' && $onlineclass == 0) $lab = 0;
//         $classremark = addslashes(trim($_POST["remark"]));

//         // Check if lab has changed
//         $sql_lab = "SELECT lab_code, res_code FROM `classschedules` WHERE `class_id` = $classId";
//         $res_lab = mysqli_query($conn, $sql_lab);
//         if ($row = mysqli_fetch_assoc($res_lab)) {
//             $reslab = $row['lab_code'];
//             $rescode = $row['res_code'];
//         }

//         if ($reslab != $selectLab[0]) {
//             $usrnm = $_SESSION['userMtsFom'];
//             $ip = $_SERVER['REMOTE_ADDR'];
//             $cancel_lab = "UPDATE reserve SET res_st=0, st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
//             mysqli_query($labconn, $cancel_lab);
//             $sqlCancelRes = "UPDATE `classschedules` SET res_code = NULL WHERE `class_id` = $classId";
//             mysqli_query($conn, $sqlCancelRes);
//         }

//         // Initialize the SQL
//         $sql = "";

//         // If lecturers are selected
//         if (isset($_POST['selectedLecturers'])) {
//             $depStaffArray = $_POST['selectedLecturers'];
//             if (!is_array($depStaffArray)) {
//                 $depStaffArray = explode(',', $depStaffArray);
//             }

//             $first = explode('-', $depStaffArray[0]);
//             $dep = intval($first[0]);
//             $staff = intval($first[1]);

//             // mysqli_query($conn, 'LOCK TABLES classtopics WRITE;');
//             // mysqli_query($conn, 'LOCK TABLES classtopics WRITE,classtopics_new WRITE,classschedules WRITE,classtopics_staff WRITE;');

//             // Main updates
//             mysqli_query($conn,"UPDATE `classtopics` SET `class_topic` = '$topic', `dep_code` = $dep, `staff` = $staff, class_group = '$group' WHERE `topic_id` = $topicId;");
//             mysqli_query($conn, "UPDATE `classtopics_new` SET `class_topic` = '$topic', `dep_code` = $dep WHERE `topic_id` = $topicId;");
//             mysqli_query($conn, "UPDATE `classschedules` SET `lab_code` = $lab, `class_remark` = '$classremark' WHERE `class_id` = $classId;");
//             // $sql .= "UPDATE `classtopics_new` SET `class_topic` = '$topic', `dep_code` = $dep WHERE `topic_id` = $topicId;";
            
//             // $sql .= "UPDATE `classschedules` SET `lab_code` = $lab, `class_remark` = '$classremark' WHERE `class_id` = $classId;";
//             mysqli_query($conn, "DELETE FROM `classtopics_staff` WHERE `topic_id` = $topicId;");
//         // mysqli_query($conn, 'UNLOCK TABLES;');

//             // Clear old and insert new staff
//             foreach ($depStaffArray as $item) {
//                 $parts = explode('-', $item);
//                 if (count($parts) < 2) continue;
//                 $staffId = intval($parts[1]);
//                 $insert = "INSERT INTO `classtopics_staff` (`topic_id`, `class_group`, `staff`) VALUES ($topicId, '$group', $staffId);";
//                 mysqli_query($conn, $insert);
//             }

//         } else {
//             // No lecturers selected — simpler update
//             // mysqli_query($conn, 'LOCK TABLES classtopics WRITE,classtopics_new WRITE,classschedules WRITE;');
//             mysqli_query($conn,"UPDATE `classtopics` SET `class_topic` = '$topic', `dep_code` = $dep, `staff` = $staff, class_group = '$group' WHERE `topic_id` = $topicId;");
//             mysqli_query($conn, "UPDATE `classtopics_new` SET `class_topic` = '$topic', `dep_code` = $dep WHERE `topic_id` = $topicId;");
//             mysqli_query($conn, "UPDATE `classschedules` SET `lab_code` = $lab, `class_remark` = '$classremark' WHERE `class_id` = $classId;");

//             // $sql .= "UPDATE `classtopics` SET `class_topic` = '$topic', class_group = '$group' WHERE `topic_id` = $topicId;";
//             // $sql .= "UPDATE `classtopics_new` SET `class_topic` = '$topic' WHERE `topic_id` = $topicId;";
//             // $sql .= "UPDATE `classschedules` SET `lab_code` = $lab, `class_remark` = '$classremark' WHERE `class_id` = $classId;";
//         }

//         // Run all updates
     

//         mysqli_query($conn, 'UNLOCK TABLES;');
//         mysqli_close($conn);
//     }
// }

// ...existing code...
if (count($_POST) > 0) {
    if ($_POST['type'] == 2) {
        require_once 'database.php';

        $id1 = $_POST['classReserveID'];
        $id2 = explode('-', $id1);
        $classId = intval($id2[0]);
        $topicId = intval($id2[1]);
        $activity = $_POST['selectActivity'];

        $topic = mysqli_real_escape_string($conn, $_POST['classTopicVal']);
        $group = addslashes($_POST['classGroupVal']);

        $classStTime = date('Y-m-d H:i', strtotime($_POST['classStTime']));
        $classEnTime = date('Y-m-d H:i', strtotime($_POST['classEnTime']));
        $selectLab = $_POST['selectLab'];
        $lab = isset($selectLab[0]) ? intval($selectLab[0]) : 0;

        $onlineclass = isset($_POST["onlinec"]) ? $_POST["onlinec"] : '';
        if ($onlineclass !== '' && $onlineclass == 0) $lab = 0;

        $classremark = mysqli_real_escape_string($conn, trim($_POST["remark"] ?? ''));
        $addStaff = $_SESSION['userMtsFom'] ?? 'unknown';

        // Get selected lecturers
        $depStaffArray = [];
        $dep = $staff = null;

        if (!empty($_POST['selectedLecturers'])) {
            $depStaffArray = is_array($_POST['selectedLecturers'])
                ? $_POST['selectedLecturers']
                : explode(',', $_POST['selectedLecturers']);

            if (count($depStaffArray) > 0) {
                $first = explode('-', $depStaffArray[0]);
                $dep = intval($first[0] ?? 0);
                $staff = intval($first[1] ?? 0);
            }
        }

        // Check if lab has changed
        $sql_lab = "SELECT lab_code, res_code FROM `classschedules` WHERE `class_id` = $classId";
        $res_lab = mysqli_query($conn, $sql_lab);
        $reslab = $rescode = null;

        if ($row = mysqli_fetch_assoc($res_lab)) {
            $reslab = $row['lab_code'];
            $rescode = $row['res_code'];
        }

        if ($reslab != $lab) {
            $usrnm = $_SESSION['userMtsFom'];
            $ip = $_SERVER['REMOTE_ADDR'];

            // Cancel old reservation
            mysqli_query($labconn, "UPDATE reserve SET res_st=0, st_change_by='$usrnm', st_change_on=NOW(), st_change_ip='$ip' WHERE res_id=$rescode");
            mysqli_query($conn, "UPDATE classschedules SET res_code=NULL WHERE class_id=$classId");
        }

        // Update data
        mysqli_query($conn, 'LOCK TABLES classtopics WRITE, classtopics_new WRITE, classschedules WRITE, classtopics_staff WRITE');

        $sql = "";
        if ($dep !== null && $staff !== null) {
            $sql .= "UPDATE classtopics SET class_topic='$topic', dep_code=$dep, staff=$staff, class_group='$group' WHERE topic_id=$topicId;";
            $sql .= "UPDATE classtopics_new SET class_topic='$topic', dep_code=$dep WHERE topic_id=$topicId;";
        } else {
            $sql .= "UPDATE classtopics SET class_topic='$topic', class_group='$group' WHERE topic_id=$topicId;";
            $sql .= "UPDATE classtopics_new SET class_topic='$topic' WHERE topic_id=$topicId;";
        }

        $sql .= "UPDATE classschedules SET lab_code=$lab, class_remark='$classremark' WHERE class_id=$classId;";
        $d = mysqli_multi_query($conn, $sql);

        // Close and reopen to reset connection for future queries
        mysqli_close($conn);
        database_conectivity();

        mysqli_query($conn, 'UNLOCK TABLES');

        if ($d) {
            // Update classtopics_staff
            mysqli_query($conn, "DELETE FROM classtopics_staff WHERE topic_id = $topicId");

            $insertSQL = "";
            foreach ($depStaffArray as $item) {
                $parts = explode('-', $item);
                if (count($parts) >= 2) {
                    $staffId = intval($parts[1]);
                    $insertSQL .= "INSERT INTO classtopics_staff (topic_id, class_group, staff) VALUES ($topicId, '$group', $staffId);";
                }
            }

            if (!empty($insertSQL)) {
                mysqli_multi_query($conn, $insertSQL);
            }

            mysqli_query($conn, 'UNLOCK TABLES');
            mysqli_close($conn);
            echo json_encode(["statusCode" => 200]);
        } else {
            mysqli_query($conn, 'UNLOCK TABLES');
            mysqli_close($conn);
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        exit;
    }
}




if(count($_POST)>0){
    if($_POST['type']==3){
        //RESCHEDULE TIME IN EVENT RESIZE
        //class_id, '-', class_topic_id
        $id1 = $_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $phpStTime = $_POST['start'];
        $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['end'];
        $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
        $editStaff = $_SESSION["userMtsFom"];
        //GET THE CLASS STATUS
       $clasStatus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT class_status  FROM classschedules  WHERE `class_id` = $classId AND class_topic_id = $topicId "));
       if($clasStatus['class_status']== 1) {
           mysqli_query($conn,'LOCK TABLES classschedules WRITE;');
           $sql= "UPDATE `classschedules` SET  `class_start` = '$classStTime', `class_end` = '$classEnTime', `edit_staff` = '$editStaff', `edit_dt` = now()   WHERE `class_id` = $classId";
           if (mysqli_query($conn, $sql)) {
               echo json_encode(array("statusCode"=>200));
           }
           else {
               echo "Error: " . $sql . "<br>" . mysqli_error($conn);
           }
           mysqli_query($conn,'UNLOCK TABLES classschedules;');
           mysqli_close($conn);
       }elseif ($clasStatus['class_status'] == 0 || $clasStatus['class_status']== 3 ){
           $addStaff = $_SESSION["userMtsFom"];
           mysqli_query($conn,'LOCK TABLES classschedules WRITE;');
           $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`,  `class_status`, `add_staff`, `add_dt` ) 
                          VALUES ($topicId, '$classStTime', '$classEnTime', 1, '$addStaff', now());";
           $sql .= "UPDATE `classschedules` SET  `class_status` = 4, `edit_staff` = '$addStaff', `edit_dt` = now()   WHERE `class_id` = $classId AND class_topic_id = $topicId ;";
           if (mysqli_multi_query($conn, $sql)) {
               echo json_encode(array("statusCode"=>200));
           }
           else {
               echo "Error: " . $sql . "<br>" . mysqli_error($conn);
           }
           mysqli_query($conn,'UNLOCK TABLES classschedules;');
           mysqli_close($conn);
       }
    }
}
if(count($_POST)>0){
    if($_POST['type']==4){
    	//check for holodays
        $phpStTime = $_POST['start'];
        $classStTime = date ('Y-m-d', strtotime($phpStTime));
        $sql = "SELECT count(*) as res FROM `holidays` WHERE `startDt` <= '$classStTime' AND '$classStTime' < `enddt`";
        $result = mysqli_query($conn,$sql);
        if ($row=mysqli_fetch_assoc($result)) {
        	$count =  $row["res"];
            echo json_encode(array("statusCode"=>$count));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
	if($_POST['type']==5){
	    //DELETE CLASS
        ////class_id, '-', class_topic_id
        /// //if the TT is not confirmedd -> delete , if confirmed - > canceled,
        $id1=$_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $rc1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT res_code FROM classschedules WHERE class_id = $classId"));
        $rescode = $rc1['res_code'];
        echo $topicId;
		$sql = "DELETE FROM `classschedules` WHERE class_id = $classId;"; 
        $sql .= "DELETE FROM `classtopics` WHERE topic_id = $topicId;";
        $sql .= "DELETE FROM `classtopics_staff` WHERE topic_id = $topicId;";
        $sql .= "DELETE FROM `classtopics_new` WHERE topic_id = $topicId;";
        if (mysqli_multi_query($conn, $sql)) {
            $usrnm = $_SESSION['userMtsFom'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $cancel_lab = "UPDATE reserve SET res_st=0,  st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
            mysqli_query($labconn, $cancel_lab );
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

		mysqli_close($conn);
	}
}
if(count($_POST)>0){
    if($_POST['type']==6){
        //CONFIRM MODULE
        //28-CARE1
        //add data to seperate table
        $id1=$_POST['id'];
        $id2 = explode('-', $id1);
        $batch = $id2[0];
        $module = trim($id2[1]);
        $sql = "UPDATE `batchmodule`  SET  	ttprogress = 1 WHERE  b_no = $batch AND m_code = '$module';";
        if ($row=mysqli_fetch_assoc($result)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==7){
        $e=fopen("y.txt","w+");
        
        //CANCEL CLASS
        ////class_id, '-', class_topic_id
       // $labconn = mysqli_connect('172.18.2.8', 'syslabreader', 'jBGsQj8D4', 'labshedule');
        $id1=$_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $dep1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT dep_code FROM dep WHERE dep_acc = '$mcode'"));
        $usrDep = $dep1['dep_code'];
        $rc1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT res_code FROM classschedules WHERE class_id = $classId"));
        $rescode = $rc1['res_code'];
        fwrite($e,"UPDATE `classschedules` SET class_status = 0 , res_code = NULL WHERE class_id = $classId AND class_topic_id = $topicId;");
        fclose($e);
        $sql = "UPDATE `classschedules` SET class_status = 0 , res_code = NULL WHERE class_id = $classId AND class_topic_id = $topicId;";
        $usrnm = $_SESSION['userMtsFom'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $cancel_lab = "UPDATE reserve SET res_st=0,  st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
        mysqli_query($labconn, $cancel_lab );
        if (mysqli_multi_query($conn, $sql)) {
            echo $id;
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==8){
        //POSTPONE CLASS
        ////class_id, '-', class_topic_id
        $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
        $id1=$_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $dep1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT dep_code FROM dep WHERE dep_acc = '$mcode'"));
        $usrDep = $dep1['dep_code'];
        $rc1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT res_code FROM classschedules WHERE class_id = $classId"));
        $rescode = $rc1['res_code'];
        $sql = "UPDATE `classschedules` SET class_status = 3, res_code = NULL WHERE class_id = $classId AND class_topic_id = $topicId;";
        $usrnm = $_SESSION['userMtsFom'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $cancel_lab = "UPDATE reserve SET res_st=0, st_change_by='$usrnm', st_change_on=now(), st_change_ip='$ip' WHERE res_id=$rescode";
        mysqli_query($labconn, $cancel_lab );
        if (mysqli_multi_query($conn, $sql)) {
            echo $id;
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==9){
        //POSTPONE CLASS
        ////class_id, '-', class_topic_id
        //print_r($_POST);
        $id1=$_POST['id'];
        $classDt = $_POST['cdt'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $getBatchMod = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT(b_no, '-', m_code) as bm  FROM classtopics WHERE topic_id = $topicId"));
        $bmcode =  $getBatchMod['bm'];
        $cd  = explode('-', $bmcode);
        $bacth = $cd[0];
        $module = $cd[1];
        $sql = "UPDATE `classschedules` SET class_start = concat('$classDt ', time(class_start)) , class_end = concat('$classDt ', time(class_end)) WHERE class_id IN (SELECT class_id FROM classtopics t JOIN classschedules c ON class_topic_id = topic_id 
WHERE b_no = $bacth AND m_code = '$module' AND DATE(class_start) =(SELECT DATE(class_start) FROM classschedules WHERE class_id=$classId))";
        //echo $sql;
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==10){
        //POSTPONE CLASS
        ////class_id, '-', class_topic_id
        //print_r($_POST);
        $id1=$_POST['id'];
        $new_class = $_POST['cdt'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $getoldDt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATE(`class_start`) as st  FROM classschedules WHERE  	class_id = $classId"));
        $oldClassDate =  $getoldDt['st'];
        $date_interval = strtotime($new_class) - strtotime($oldClassDate);
        $date_interval = round($date_interval / (60 * 60 * 24));
        $getBatchMod = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT(b_no, '-', m_code) as bm  FROM classtopics WHERE topic_id = $topicId"));
        $bmcode =  $getBatchMod['bm'];
        $cd  = explode('-', $bmcode);
        $bacth = $cd[0];
        $module = $cd[1];
        $dayofTheWeek = date('w', strtotime($oldClassDate));
        for($i=0 ; $dayofTheWeek <= 5; $i++ ){
            $sql = "UPDATE `classschedules` SET class_start = concat('$new_class', time(class_start)) , class_end = concat('$new_class', time(class_end)) 
            WHERE class_id IN (SELECT class_id FROM classtopics t JOIN classschedules c ON class_topic_id = topic_id 
WHERE b_no = $bacth AND m_code = '$module' AND DATE(class_start) = '$oldClassDate'";
            $dayofTheWeek++;
            $new_class = date('Y-m-d', strtotime($new_class. " + 1 days "));
            //check holiday
            //check weekend
            //add oneday to date
        }

       // $sql = "UPDATE `classschedules` SET class_start = concat('$classDt ', time(class_start)) , class_end = concat('$classDt ', time(class_end)) WHERE class_id IN (SELECT class_id FROM classtopics t JOIN classschedules c ON class_topic_id = topic_id
        //WHERE b_no = $bacth AND m_code = '$module' AND DATE(class_start) =(SELECT DATE(class_start) FROM classschedules WHERE class_id=$classId))";

        //echo $sql;
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>
