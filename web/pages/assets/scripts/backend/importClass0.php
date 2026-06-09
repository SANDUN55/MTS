<?php
//echo "ok";
//echo "<br>";
include 'database.php';
database_conectivity();
$sql_getDates = "SELECT MAX(DATE(class_start)) AS mxDt, MIN(DATE(class_start)) AS mnDt, DATEDIFF(DATE(MAX(class_start)), DATE(MIN(class_start))) AS 'val' 
                          FROM `classschedules` 
                          JOIN classtopics ON `class_topic_id` =  classtopics.topic_id
                          WHERE classtopics.b_no = 34 AND classtopics.m_code='FOUN1'";
//echo $sql_getDates;
$res_getDates = mysqli_query($conn, $sql_getDates);
$row1 = mysqli_fetch_assoc($res_getDates);
$scheduleDt = '2024-02-01';
print_r($row1);
echo "<br>";
$maxDate = $row1['mxDt'];
$minDate = $row1['mnDt'];
$durationDt = $row1['val'];
$prevBatchFirstDate = $minDate;
$lastDt = $scheduleDt;
$a=0;
for($x=0; $x<=$durationDt; $x++){
    $val1 = " + " . $x . " days";
    echo $val1;
    echo "**";
    $minDate = date('Y-m-d', strtotime($prevBatchFirstDate. $val1));
    echo $minDate;
    $dayOfWeek = date('w', strtotime($minDate));
    $a=$x;
    if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
        echo 'The date is weekday';
       // echo "<br>";
        $val2 = " + " . $a . " days";
        $scheduleDt = date('Y-m-d', strtotime($lastDt. $val2));
        echo $scheduleDt;
        $dayOfWeek = date('w', strtotime($scheduleDt));
        if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
            echo 'NEW date is weekday';
            $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM `holidays` WHERE ('$scheduleDt' BETWEEN `startDt` AND `enddt`)"));
            $chkHoliday = $getHoliday['ct'];
            if($chkHoliday==0){
                echo 'NOT HOLIDAY ';
            }else{
                echo 'HOLIDAY ';
            }
        }else{
            echo 'NEW date is WEEKEND';
        }
        echo "<br>";
    }else{
        echo '--The date is weekend';
        //echo "<br>";
        $scheduleDt = date('Y-m-d', strtotime($lastDt. $val2));
        echo $scheduleDt;
        $dayOfWeek = date('w', strtotime($scheduleDt));
        if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
            echo 'NEW date is weekday';
            $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM `holidays` WHERE ('$scheduleDt' BETWEEN `startDt` AND `enddt`)"));
            $chkHoliday = $getHoliday['ct'];
            if($chkHoliday==0){
                echo 'NOT HOLIDAY ';
            }else{
                echo 'HOLIDAY ';
            }
        }else{
            echo 'NEW date is WEEKEND';
            ++$a;

        }
        echo "<br>";


    }
}




/*
$prevBatchFirstDate = minDate;
for($x=0; $x<=$val; $x++){
            $val1 = " + " . $x . " days";
			
			$minDate = date('Y-m-d', strtotime($prevBatchFirstDate. $val1));
			$dayOfWeek = date('w', strtotime($minDate));
			//check minDate date is weekend
			if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
			
			
			
			
			
			
								
								
								 $date = date('Y-m-d', strtotime($classStDate. $val1));
								$dayOfWeek = date('w', strtotime($date));
								//check reserving date is weekend
								if ($dayOfWeek <> 0 && $dayOfWeek <> 6) {
									
									//check reserving date faculty holiday
									
									 $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM `holidays` WHERE ('$date' BETWEEN `startDt` AND `enddt`)"));
									$chkHoliday = $getHoliday['ct'];
									if($chkHoliday==0){
									   // echo 'The date is weekday';
									   
										//get all classes on the date previous batch first date (min start Date)
											$getTopic = "SELECT topic_id , TIME(class_start) , TIME(class_end), lab_code
															FROM `classschedules` 
															JOIN classtopics ON `class_topic_id` =  classtopics.topic_id 
															JOIN activity ON activity.a_id = classtopics.activity
															WHERE DATE(`class_start`)= $minDate AND classtopics.b_no = 34 AND classtopics.m_code='FOUN1'
															AND activity.a_type='N';";
														
											For each topic ID {
												
												$classStTime1 = $reserving date . ' ' . $classStTime;
												$classEnTime1 = $reserving date . ' ' . $classEnTime;
												
												 $sql_insetprev = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
												  SELECT $batch, '$mod', `activity`,`activity_no`,`class_topic`,`class_group`,`dep_code`,`staff` FROM `classtopics` WHERE  `topic_id` = $topic";
						   
													//get last insert id
											
											
													  $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`, `add_staff`, `class_remark` ) 
															VALUES ($submitID, '$classStTime1', '$classEnTime1', $lab, 1, '$addStaff', '$classremark');";
											
											}
									   
									   
									   
									   
									}
									
								}
			
			
			}
						
						
						
						
			
			
			 $sql_insetprev = "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
                              SELECT $batch, '$mod', `activity`,`activity_no`,`class_topic`,`class_group`,`dep_code`,`staff` FROM `classtopics` WHERE  `topic_id` = $topic";
       
			
}
	*/
?>