<?php
//echo "ok";
//echo "<br>";
include 'database.php';
database_conectivity();
$newBatch = '32';
$prvBatch = $newBatch - 1;
$mod = 'PDFM2';
$scheduleDt = '2024-02-22';
/*$sql_getDates = "SELECT DISTINCT(DATE(class_start)) AS dts
                        FROM `classschedules` 
                        JOIN classtopics ON `class_topic_id` = classtopics.topic_id 
                        JOIN activity ON activity.a_id = classtopics.activity 
                        WHERE classtopics.b_no = 34 AND classtopics.m_code='FOUN1' AND activity.a_type='N';";*/

$sql_getDates = "SELECT DISTINCT(DATE(class_start)) AS dts , GROUP_CONCAT(topic_id) AS tps 
                    FROM `classschedules` 
                    JOIN classtopics ON `class_topic_id` =  classtopics.topic_id 
                    JOIN activity ON activity.a_id = classtopics.activity
                    WHERE classtopics.b_no = $prvBatch AND classtopics.m_code = '$mod'
                    AND activity.a_type = 'N'
                    GROUP BY dts;";


//echo $sql_getDates;
$res_getDates = mysqli_query($conn, $sql_getDates);
$prvBatch_dates = array(); $prvBatch_dates_topics = array(); $a = 0;
while($row1 = mysqli_fetch_assoc($res_getDates)){
    $prvBatch_dates[$a] = $row1['dts'];
    $prvBatch_dates_topics[$a] = $row1['tps'];
    $a++;
}
/*echo "<pre>";
print_r($prvBatch_dates);
print_r($prvBatch_dates_topics);
echo "/<pre>";*/
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
        $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM `holidays` WHERE ('$minDate' BETWEEN `startDt` AND `enddt`)"));
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
    //$classDate = $prvBatch_dates[$x];
    $prvTopics_arr = explode(',', $prvBatch_dates_topics[$x]);
    $prvTopics_arr_count = count($prvTopics_arr);
    $classDate = $newDates[$x];
    echo $newDates[$x];
    for($y=0;$y<$prvTopics_arr_count;$y++){
        $topic = $prvTopics_arr[$y];
        $sqlInTopicNewBatch =  "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `activity_no`, `class_topic`, `class_group`, `dep_code`, `staff`) 
												  SELECT $newBatch, '$mod', `activity`,`activity_no`,`class_topic`,`class_group`,`dep_code`,`staff` 
												  FROM `classtopics` WHERE  `topic_id` = $topic";
        echo $sqlInTopicNewBatch; echo "<br>";
        $submitID = mysqli_insert_id($conn);
/*        $sql2 = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`,  `class_status`)
        SELECT $submitID, `class_start`,`class_end`,`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = $topic AND `class_id` = $class";*/
        $sqlInsertSchedule = "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`,  `class_status`) 
                                  SELECT $submitID, concat('$classDate ', time(`class_start`)), concat('$classDate  ', time(`class_end`)),`lab_code`,`class_status` 
                                  FROM `classschedules` 
                                  WHERE  `class_topic_id` = $topic";
        echo $sqlInsertSchedule; echo "<br>";
        //echo $sqlInsertSchedule;
        // SELECT 1074, concat('2024-02-01 ', time(`class_start`)), concat('2024-02-01  ', time(`class_end`)),`lab_code`,`class_status` FROM `classschedules` WHERE  `class_topic_id` = 1074;
        //$classStTime1 = $date . ' ' . $classStTime;
       // $classEnTime1 = $date . ' ' . $classEnTime;
    }

    /*$getTopic = "SELECT topic_id , TIME(class_start) , TIME(class_end), lab_code
                    FROM `classschedules` 
                    JOIN classtopics ON `class_topic_id` =  classtopics.topic_id 
                    JOIN activity ON activity.a_id = classtopics.activity
                    WHERE DATE(`class_start`)= $classDate AND classtopics.b_no = 34 AND classtopics.m_code='FOUN1'
                    AND activity.a_type='N';";*/
}


?>