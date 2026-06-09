 <?php
include 'database.php';
if(count($_POST)>0){
	if($_POST['type']==1){
        //getBatch=31&getMod=ALNU1%20&selectActivity=3&classTopic=CBD%20-01&classType=G
        //&activity-no=1&group-count=2&group%5B%5D=A-1&activityDate%5B%5D=2020-11-23&activityStTime%5B%5D=10%3A00&activityEnTime%5B%5D=11%3A00&group%5B%5D=A-2&activityDate%5B%5D=2020-11-24&activityStTime%5B%5D=10%3A00&activityEnTime%5B%5D=11%3A00&selectAcademicDep=&selectLab=&classStTime=2021-01-01T10%3A30%3A00&classEnTime=2021-01-01T11%3A30%3A00&type=1
        extract($_POST);
        $bno = $_POST['getBatch'];
        $module = $_POST['getMod'];
        $activity = $_POST['selectActivity'];
        $topic = addslashes(trim($_POST['classTopic']));
        $activityNo = $_POST['activity-no'];
        $classType = $_POST['classType'];

       // $phpStTime = $_POST['classStTime'];
        //$classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
       // $phpEnTime = $_POST['classEnTime'];
       // $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));

        if($classType == 'G'){
            $groupCount  = $_POST['group-count'];
            for($x = 0 ; $x < $groupCount ; $x++) {
                $groupN = $activityGroup[$x];
                $StTime = $activityDate[$x].' '.$activityStTime[$x];
                $EnTime = $activityDate[$x].' '.$activityEnTime[$x];
                mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
                $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `activity_no`, `class_group`) VALUES ( $bno, '$module', '$activity', '$topic', $activityNo, '$groupN');";
                mysqli_query($conn, $sql);
                $submitID=mysqli_insert_id($conn);
                $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `class_status`) VALUES ($submitID, '$StTime', '$EnTime', 1);";
                mysqli_query($conn, $sql2);
                mysqli_query($conn,'UNLOCK TABLES classtopic;');
                echo "<br>"; echo $sql;echo "<br>"; echo $sql2;
            }
            //echo json_encode(array("statusCode"=>200));
            //echo $sql;echo "<br>"; echo $sql2;

        }elseif($classType == 'N'){
           // echo "N";
            $lab = $_POST['selectLab'];
            $depStaffArray=$_POST['selectAcademicDep'];
            $staffDepts=explode('-',$depStaffArray);
            $dep=$staffDepts[0];
            $staff=$staffDepts[1];
           // mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
            $sql = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `dep_code`, `staff`) VALUES ( $bno, '$module', '$activity', '$topic', $dep, $staff);";
            //echo "<br>"; echo $sql;
            mysqli_query($conn, $sql);
            $submitID=mysqli_insert_id($conn);
            $sql = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`)  VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1);";
           // echo "<br>"; echo $sql;
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            }
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            //mysqli_query($conn,'UNLOCK TABLES classtopic;');
           // echo $sql;echo "<br>"; echo $sql2;


        }

        mysqli_close($conn);




	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
        //&classReserveID=3-1&type=2
        //class_id, '-', class_topic_id
        //NEED TO REVISE CODE FRO CHANGED
        //mphase=&selectActivity=22
        //&classTopicVal=%20Introduction%20to%20Phase%20II%20
        //&selectAcademicDep=8-53
        //&selectLab=21
        //&classStTime=2020-11-23%2013%3A00%3A00
        //&classEnTime=2020-11-23%2014%3A00%3A00
        //&classDetails=Lecture%20%3Cbr%3E%20Introduction%20to%20Phase%20II%20%3Cbr%3E%20Dr.%20Shamila%20T%20De%20Silva%20%3Cbr%3ETute%20Room%20-%20105
        //&classReserveID=14-8&type=2
		$id1 = $_POST['classReserveID'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $topicId = $id2[1];
		$activity = $_POST['selectActivity'];
		$topic = $_POST['classTopicVal'];
        $depStaffArray = $_POST['selectAcademicDep'];
        $staffDepts = explode('-',$depStaffArray);
        $dep = $staffDepts[0];
        $staff = $staffDepts[1];
        $phpStTime = $_POST['classStTime'];
        $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['classEnTime'];
        $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
        $lab = $_POST['selectLab'];
        mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
        $sql = "UPDATE `classtopics`  SET  `activity` = $activity, `class_topic` = '$topic',  `dep_code` = $dep, `staff` = $staff WHERE `topic_id` = $topicId;";
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
        ////class_id, '-', class_topic_id
        $id1 = $_POST['id'];
        $id2 = explode('-', $id1);
        $classId = $id2[0];
        $phpStTime = $_POST['start'];
        $classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
        $phpEnTime = $_POST['end'];
        $classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
        mysqli_query($conn,'LOCK TABLES classschedules WRITE;');
        $sql= "UPDATE `classschedules` SET  `class_start` = '$classStTime', `class_end` = '$classEnTime' WHERE `class_id` = $classId";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_query($conn,'UNLOCK TABLES classschedules;');
        mysqli_close($conn);
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
        ////class_id, '-', class_topic_id
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
?>
