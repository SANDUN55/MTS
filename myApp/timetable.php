<?php
include 'database.php';
if(count($_POST)>0){
	if($_POST['type']==13){
	    //136-166136-166
        $id1 = $_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $phpStTime = $_POST['start'];
        $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['end'];
        $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
        $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`) 
SELECT $topicId, '$classStTime', '$classEnTime', lab_code, 1 FROM `classschedules` WHERE class_id = $classId;";
        $sql .=  "UPDATE `classschedules`  SET  `class_status` = 2 WHERE `class_id` = $classId AND class_topic_id = $topicId";
        echo $sql;
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
    if($_POST['type']==14){
        //146-171
        $id1 = $_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
        $sql =  "UPDATE `classschedules`  SET  `class_status` = 0 WHERE `class_id` = $classId AND class_topic_id = $topicId";
        //echo $sql;
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
?>
