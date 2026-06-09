<?php
include 'database.php';
//ini_set('display_errors', 1);
$bno = 31;
$module = 'ALNU1';
$activity = '2';
$topic = 'Practical-01';
$activityNo = 2;


// Add days to date and display it


//$phpStTime = $_POST['classStTime'];
//$classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
//$phpEnTime = $_POST['classEnTime'];
//$classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
$sql = '';
$sql2 = '';
mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
    $classDate = '2023-12-13';
    $classEnDate = '2023-12-19';
    $classSt = '08:00';
    $classEn = '12:30';
echo date('Y-m-d', strtotime($classDate. ' + 1 days'));
echo "<br>";
/*$datediff = $classEnDate - $classDate;
echo $datediff;
echo ($datediff / (60 * 60 * 24));*/


$datetime1 = date_create($classDate);
$datetime2 = date_create($classEnDate);
$interval = date_diff($datetime1, $datetime2);
$val =  $interval->format('%a');
echo $val;


/*
$date1 = new DateTime($classDate);
$date2 = new DateTime($classEnDate);
$interval = $date1->diff($date2);
$int = $interval->days;
echo $date1;
//echo "difference " . $interval->days . " days ";
echo "difference " . $int . " days ";*/

echo "<pre>";
for($x=1;$x<$val;$x++){
    //echo $date1;
    $val1 = " + " . $x . " days";
    echo date('Y-m-d', strtotime($classDate. $val1));
    $date = date('Y-m-d', strtotime($classDate. $val1));
    $dayOfWeek = date('w', strtotime($date));
    if ($dayOfWeek == 0) {
        echo 'The date is Sunday';
        $x=$x+1;
       // exit;
    } elseif($dayOfWeek == 6){
        echo 'The date is Saturday';
        //$x=$x+1;
        //exit;
    } else{
        echo "SELECT COUNT(*) AS ct FROM `holidays` WHERE ('$date' BETWEEN `startDt` AND `enddt`)";
        $getHoliday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS ct FROM `holidays` WHERE ('$date' BETWEEN `startDt` AND `enddt`)"));
        $chkHoliday = $getHoliday['ct'];
        echo "---";
        echo $chkHoliday;
        if($chkHoliday = 0){
            echo 'The date is weekday';

        }else{
            echo 'The date is Holiday';

        }
        echo "---";
        //check holiday
       // ;

    }

  //  echo date('Y-m-d', strtotime($classDate. ' + 1 days'));
    //echo date('Y-m-d', strtotime($classDate.' + 1 days'));
echo "<br>";

}

echo "</pre>";

//echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";

// shows the total amount of days (not divided into years, months and days like above)


/*
    for($x = 0 ; $x < $groupCount ; $x++) {
        $group = $group[$x];
        $StTime = $classDate[$x].' '.$classSt[$x];
        $EnTime = $classDate[$x].' '.$classEn[$x];
       // $classStTime = date ('Y-m-d H:i', strtotime($StTime));
       // $classEnTime = date ('Y-m-d H:i', strtotime($EnTime));
        $sql .= "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `class_group`, `dep_code`, `staff`) 
VALUES ( $bno, '$module', '$activity', '$topic', '$group', $dep, $staff);";
       // mysqli_query($conn, $sql);
       // $submitID=mysqli_insert_id($conn);
        $sql2 .= "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`) 
VALUES ($submitID, '$StTime', '$EnTime', $lab, 1);";
    }

*/



mysqli_query($conn,'UNLOCK TABLES classtopic;');
mysqli_close($conn);

echo $sql;echo "<br>"; echo $sql2;










/*
global $conn;
$result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm, dep_code FROM staff WHERE st_cat=1 ORDER BY div_nm, firstname ASC;");
$output='';
$outgroup = '';
$output .= '<select name="selectAcademic[]" id="selectAcademic" class="form-control" required>';
$output .= '<option value="" class="form-control">select lecturer</option>';
while($row = mysqli_fetch_array($result)) {
    $group[$row['div_nm']][] = $row;
}
foreach ($group as $key => $values){
    $output .= '<optgroup label="'.$key.'">';
    foreach ($values as $value)
    {
        $output .= '<option value="' . $value["st_id"].'-'.$value["dep_code"]. '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. '</option>';
    }
    $output .= '<option value="' . $value["dep_code"].'-'.$value["dep_code"] . '">' . $key. '</option>';
    $output .= '</optgroup>';
}
$output .= '</select>
         <div class="invalid-feedback">
                     Please select Academic Staff.
                 </div>';
echo "<pre>";
print_r($group);
echo "</pre>";
*/


?>