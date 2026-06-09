
<?php
include 'database.php';

$bno = $_GET['custom_param1'];
$mod = $_GET['custom_param2'];
$stid=33;

$bno=30;
$mod='alnu1';
$dataArray = array();

$query = " SELECT CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, CONCAT(t_nm, '. ', firstname, ' ', surname, ' ') AS stVAL , a_name, lab_nm FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN staff ON classtopics.staff = staff.st_id
JOIN activity ON a_id = activity
LEFT JOIN lab on lab.lab_code = classschedules.lab_code
WHERE classtopics.dep_code <> classtopics.staff AND class_status = 1 AND b_no = $bno AND m_code = '$mod' AND staff = $stid
ORDER BY `class_id`;";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)) {
   $title=$row["a_name"].' : '.$row["class_topic"];
   $description=$row["stVAL"].$row["div_nm"].' : '.$row["lab_nm"];
   $dataArray[] = array(
        "id" => $row["class_reserve_id"],
        "title"   => $title.' : '.$description,
        "start"   => $row["class_start"],
        "end"   => $row["class_end"],
        );
    }
echo json_encode($dataArray);

?>