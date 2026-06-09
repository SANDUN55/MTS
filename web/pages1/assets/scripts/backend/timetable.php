<?php
include 'database.php';
database_conectivity();
session_start();
if(count($_POST)>0){
	if($_POST['type']==1){
        $bno = $_POST['getBatch'];
        $module = $_POST['getMod'];
        $activity = $_POST['selectActivity'];
        $topic = addslashes(trim($_POST['classTopic']));
        $phpStTime = $_POST['classStTime'];
        $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['classEnTime'];
        $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
        $labArray = $_POST['selectLab'];
        $lab = $labArray[0];
        $depStaffArray = $_POST['selectAcademicDep'];
        $staffDepts = explode('-',$depStaffArray);
        $dep = $staffDepts[0];
        $staff = $staffDepts[1];
        $addStaff = $_SESSION["userMtsFom"];
        mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
        $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `class_group`, `dep_code`, `staff`) 
VALUES ( $bno, '$module', '$activity', '$topic', 'A', $dep, $staff);";
        mysqli_query($conn, $sql);
        $submitID=mysqli_insert_id($conn);
        $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`, `add_staff`) 
VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff');";
        if (mysqli_query($conn, $sql)) {

            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_query($conn,'UNLOCK TABLES classtopic;');
        mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
        //mphase=
        //&selectActivity=15
        //&classTopicVal=Practical%2001%20qqqqq
        //&classGroupVal=C
        //&selectLab%5B%5D=9
        //&classStTime=2021-01-28%2013%3A00%3A00
        //&classEnTime=2021-01-28%2014%3A00%3A00
        //&classDetails=Practical
        //&classTitle=Practical%2001%20%3A%20C%20%3A%20%20%3A%20Tute%20Room%20C
        //&classReserveID=71-74
        //&type=2
		$id1 = $_POST['classReserveID'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
		$activity = $_POST['selectActivity'];
		$topic = addslashes($_POST['classTopicVal']);
        $group = addslashes($_POST['classGroupVal']);
        $phpStTime = $_POST['classStTime'];
        $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['classEnTime'];
        $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
        $selectLab = $_POST['selectLab'];
        $lab = $selectLab[0];
        if(isset($_POST['selectAcademicDep'])){
            $depStaffArray = $_POST['selectAcademicDep'];
            $staffDepts = explode('-',$depStaffArray);
            $dep = $staffDepts[0];
            $staff = $staffDepts[1];
            mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
            $sql = "UPDATE `classtopics`  SET  `class_topic` = '$topic',  `dep_code` = $dep, `staff` = $staff, class_group 	= '$group' WHERE `topic_id` = $topicId;";
        }else{
            mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
            $sql = "UPDATE `classtopics`  SET  `class_topic` = '$topic',  class_group 	= '$group' WHERE `topic_id` = $topicId;";
        }
        //mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
        //$sql = "UPDATE `classtopics`  SET  `activity` = $activity, `class_topic` = '$topic',  `dep_code` = $dep, `staff` = $staff, class_group 	= '$group' WHERE `topic_id` = $topicId;";
        $sql .= "UPDATE `classschedules`  SET  `lab_code` = $lab WHERE `class_id` = $classId";
        if (mysqli_multi_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_query($conn,'UNLOCK TABLES classtopic;');
        mysqli_close($conn);
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
		$sql = "DELETE FROM `classschedules` WHERE class_id = $classId;";
        $sql .= "DELETE FROM `classtopics` WHERE topic_id = $topicId;";
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
        //CANCEL CLASS
        ////class_id, '-', class_topic_id
        $id1=$_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $sql = "UPDATE `classschedules` SET class_status = 0 WHERE class_id = $classId AND class_topic_id = $topicId;";
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
        $id1=$_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $sql = "UPDATE `classschedules` SET class_status = 3 WHERE class_id = $classId AND class_topic_id = $topicId;";
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
