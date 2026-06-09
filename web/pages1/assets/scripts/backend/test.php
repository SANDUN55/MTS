<?php
include 'database.php';

//getBatch=31
//&getMod=ALNU1%20
//&selectActivity=2
//&classTopic=Practical%2001
//&classType=G
//&activity-no=1
//&group-count=2
//&group%5B%5D=A-1
//&classDate%5B%5D=2020-11-23
//&classStTime%5B%5D=11%3A00
//&classEnTime%5B%5D=12%3A00
//&group%5B%5D=A-2
//&classDate%5B%5D=2020-11-24
//&classStTime%5B%5D=11%3A00
//&classEnTime%5B%5D=12%3A00
//&selectAcademicDep=
//&selectLab=
//&classStTime=2021-01-01T10%3A15%3A00
//&classEnTime=2021-01-01T11%3A00%3A00
//&type=1
$bno = 31;
$module = 'ALNU1';
$activity = '2';
$topic = 'Practical-01';
$activityNo = 2;
$classType = 'G';

//$phpStTime = $_POST['classStTime'];
//$classStTime = date ('Y-m-d H:i', strtotime($phpStTime));
//$phpEnTime = $_POST['classEnTime'];
//$classEnTime = date ('Y-m-d H:i', strtotime($phpEnTime));
$sql = '';
$sql2 = '';
mysqli_query($conn,'LOCK TABLES classtopic WRITE;');
if($classType == 'G'){
    $groupCount  = 2;
    $classDate = '2020-11-23';
    $classSt = '11:00';
    $classEn = '12:00';



    for($x = 0 ; $x < $groupCount ; $x++) {
        $group = $group[$x];
        $StTime = $classDate[$x].' '.$classSt[$x];
        $EnTime = $classDate[$x].' '.$classEn[$x];
       // $classStTime = date ('Y-m-d H:i', strtotime($StTime));
       // $classEnTime = date ('Y-m-d H:i', strtotime($EnTime));
        $sql .= "INSERT INTO `classtopics` (`b_no`, `m_code`, `activity`, `class_topic`, `class_group`, `dep_code`, `staff`) 
VALUES ( $bno, '$module', '$activity', '$topic', '$group', $dep, $staff);";
        mysqli_query($conn, $sql);
        $submitID=mysqli_insert_id($conn);
        $sql2 .= "INSERT INTO `classschedules` (`class_topic_id`, `class_start`, `class_end`, `lab_code`, `class_status`) 
VALUES ($submitID, '$StTime', '$EnTime', $lab, 1);";
    }


}elseif($classType == 'N'){

}
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