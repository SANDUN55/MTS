
<?php
include 'env/database.php';

$bno = $_GET['custom_param1'];
$mod = $_GET['custom_param2'];

//$bno=30;
//$mod='alnu1';
$dataArray = array();
/*
 * SELECT class_reserve_id, class_start, class_end, class_topic, class_group,t_nm, firstname, surname, a_name, lab_nm FROM `classreserve`
JOIN staff ON classreserve.staff=staff.st_id
JOIN activity ON a_id=activity
JOIN lab on lab_code=venue
WHERE classreserve.dep_code<>classreserve.staff and status=1 order by `class_reserve_id`
*/
//20/12/2023 APPLIED COLOR TO DEPARTMENTS
$query = " SELECT CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, CONCAT(t_nm, '. ', firstname, ' ', surname, ' ') AS stVAL , a_name, lab_nm, class_remark,  div_color 
 FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN staff ON classtopics.staff = staff.st_id
LEFT JOIN divisions ON staff.dep_code = div_id
JOIN activity ON a_id = activity
LEFT JOIN lab on lab.lab_code = classschedules.lab_code
WHERE classtopics.dep_code <> classtopics.staff AND class_status = 1 AND b_no = $bno AND m_code = '$mod'
ORDER BY `class_id`;";


$query .= "SELECT CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, div_nm, a_name, lab_nm, class_remark, div_color FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN divisions ON div_id = dep_code 
JOIN activity ON a_id = activity
LEFT JOIN lab on lab.lab_code = classschedules.lab_code
WHERE classtopics.dep_code = classtopics.staff  AND class_status = 1 AND b_no = $bno AND m_code = '$mod'
ORDER BY `class_id`;";




//echo $query;
/* execute multi query */
if (mysqli_multi_query($conn, $query)) {
    do {
        /* store first result set */
        if ($result = mysqli_store_result($conn)) {
            while ($row = mysqli_fetch_assoc($result)) {
                //$title=$row["a_name"].'<br>'.$row["class_topic"].'-'.$row["t_nm"].' - '.$row["firstname"].' \r'. $row["surname"].' - '.$row["div_nm"].'-'.$row["lab_nm"];
                $title=$row["a_name"].' : '.$row["class_topic"].' : '.$row["class_group"];
                $description=$row["stVAL"].$row["div_nm"].' : '.$row["lab_nm"];
                $dataArray[] = array(
                    "id" => $row["class_reserve_id"],
                    "title"   => $title.' : '.$description. ',' . stripslashes($row["class_remark"]),
                    "start"   => $row["class_start"],
                    "end"   => $row["class_end"],
                    "color" => $row["div_color"],
                );
            }
            mysqli_free_result($result);
        }
        /* print divider
        if (mysqli_more_results($conn)) {
            $title=$row["a_name"].' , '.$row["class_topic"].' -<br> '.$row["div_nm"].$row["lab_nm"];
            $dataArray[] = array(
                "id" => $row["class_reserve_id"],
                "title"   => $title,
                "start"   => $row["class_start"],
                "end"   => $row["class_end"]
            );
        }*/
    } while (mysqli_next_result($conn));
}





/*
$result = mysqli_query($conn,$query);

					
while($row = mysqli_fetch_array($result)) {
	$title=$row["a_name"].' ,\\n '.$row["class_topic"].' -<br>'.$row["t_nm"].' '.$row["firstname"].' \r'. $row["surname"].' - '.$row["lab_nm"];
$dataArray[] = array(
  "id" => $row["class_reserve_id"],
  "title"   => $title,
  "start"   => $row["class_start"],
  "end"   => $row["class_end"]
 );
}*/
//echo "<pre>";
//var_dump($dataArray);
//print_r($dataArray);
//echo "</pre>";
echo json_encode($dataArray);

?>