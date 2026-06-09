<?php
include 'env/database.php';
$bno = $_GET['custom_param1'];

$dataArray = array();
$o=fopen("query.txt","w+");

// $query="SELECT       CONCAT(class_id, '-', class_topic_id) AS class_reserve_id,       class_topic, class_start, class_end, classtopics_staff.class_group,       GROUP_CONCAT(CONCAT(t_nm, '. ', firstname, ' ', surname) SEPARATOR ', ') AS stVAL,       a_name, lab_nm, classtopics_new.m_code, div_color  FROM classschedules  JOIN classtopics_new ON class_topic_id = topic_id  LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics_new.topic_id  LEFT JOIN staff ON classtopics_staff.staff = staff.st_id  LEFT JOIN divisions ON staff.dep_code = div_id  JOIN activity ON a_id = activity  JOIN batchmodule ON classtopics_new.m_code = batchmodule.m_code  LEFT JOIN lab ON lab.lab_code = classschedules.lab_code  WHERE classtopics_new.dep_code <> classtopics_staff.staff      AND class_status = 1      AND classtopics_new.b_no ='32'      AND batchmodule.b_no = '32'  GROUP BY class_id, class_topic_id  ORDER BY class_id;SELECT       CONCAT(class_id, '-', class_topic_id) AS class_reserve_id,      class_topic,       class_start,       class_end,       classtopics_staff.class_group,      div_color,      div_nm,      a_name,      lab_nm,      classtopics_new.m_code  FROM classschedules  JOIN classtopics_new       ON class_topic_id = topic_id  LEFT JOIN divisions       ON div_id = dep_code  JOIN activity       ON a_id = activity  JOIN batchmodule       ON classtopics_new.m_code = batchmodule.m_code  LEFT JOIN lab       ON lab.lab_code = classschedules.lab_code  JOIN classtopics_staff      ON classtopics_new.dep_code = classtopics_staff.staff  WHERE class_status = 1    AND classtopics_new.b_no = '32'    AND batchmodule.b_no =  '32'  ORDER BY class_id;";

$query = "SELECT 
    CONCAT(class_id, '-', class_topic_id) AS class_reserve_id, 
    class_topic, class_start, class_end, classtopics_staff.class_group, 
    GROUP_CONCAT(CONCAT(t_nm, '. ', firstname, ' ', surname) SEPARATOR ', ') AS stVAL, 
    a_name, lab_nm, classtopics_new.m_code, div_color
FROM classschedules
JOIN classtopics_new ON class_topic_id = topic_id
LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics_new.topic_id
LEFT JOIN staff ON classtopics_staff.staff = staff.st_id
LEFT JOIN divisions ON staff.dep_code = div_id
JOIN activity ON a_id = activity
JOIN batchmodule ON classtopics_new.m_code = batchmodule.m_code
LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
WHERE classtopics_new.dep_code <> classtopics_staff.staff
    AND class_status = 1
    AND classtopics_new.b_no ='". explode(",",$bno)[0]."'
    AND batchmodule.b_no = '". explode(",",$bno)[0]."'
GROUP BY class_id, class_topic_id
ORDER BY class_id;";


$query .= "SELECT 
    CONCAT(class_id, '-', class_topic_id) AS class_reserve_id,
    class_topic, 
    class_start, 
    class_end, 
    classtopics_staff.class_group,
    div_color,
    div_nm,
    a_name,
    lab_nm,
    classtopics_new.m_code
FROM classschedules
JOIN classtopics_new 
    ON class_topic_id = topic_id
LEFT JOIN divisions 
    ON div_id = dep_code
JOIN activity 
    ON a_id = activity
JOIN batchmodule 
    ON classtopics_new.m_code = batchmodule.m_code
LEFT JOIN lab 
    ON lab.lab_code = classschedules.lab_code
JOIN classtopics_staff
    ON classtopics_new.topic_id = classtopics_staff.topic_ids
WHERE class_status = 1
  AND classtopics_new.b_no = '". explode(",",$bno)[0]."'
  AND batchmodule.b_no =  '". explode(",",$bno)[0]."'
ORDER BY class_id;";
// error_log($query);
if (mysqli_multi_query($conn, $query)) {
    do {
        /* store first result set */
        if ($result = mysqli_store_result($conn)) {
            while ($row = mysqli_fetch_assoc($result)) {
                //$title=$row["a_name"].'<br>'.$row["class_topic"].'-'.$row["t_nm"].' - '.$row["firstname"].' \r'. $row["surname"].' - '.$row["div_nm"].'-'.$row["lab_nm"];
                $title=$row["m_code"].' ' .$row["a_name"].' : '.$row["class_topic"].' : '.$row["class_group"];
                $description=$row["stVAL"].$row["div_nm"].' : '.$row["lab_nm"];
                $st=new DateTime($row["class_start"]);
                $st=$st->format(DateTime::ATOM);
                $ed=new DateTime($row["class_end"]);
                $ed=$ed->format(DateTime::ATOM);
                $dataArray[] = array(
                    "id" => $row["class_reserve_id"],
                    "title"   => $title.' : '.$description,
                    "start"   => $st,
                    "end"   => $ed,
                    "color" => $row["div_color"],
                );
            }
            mysqli_free_result($result);
        }
        // print divider
        if (mysqli_more_results($conn)) {
            $title=$row["a_name"].' , '.$row["class_topic"].' - '.$row["div_nm"].$row["lab_nm"];
            $st=new DateTime($row["class_start"]);
            $st=$st->format(DateTime::ATOM);
            $ed=new DateTime($row["class_end"]);
            $ed=$ed->format(DateTime::ATOM);
            $dataArray[] = array(
                "id" => $row["class_reserve_id"],
                "title"   => $title,
                "start"   => $st,
                "end"   => $ed,
            );
        }
    } while (mysqli_next_result($conn));
}

// $result = mysqli_query($conn,$query);
// $result = mysqli_query($conn,$tp);

					
// while($row = mysqli_fetch_array($result)) {
// 	$title=$row["a_name"].' ,\\n '.$row["class_topic"].' -<br>'.$row["t_nm"].' '.$row["firstname"].' \r'. $row["surname"].' - '.$row["lab_nm"];
// $dataArray[] = array(
//   "id" => $row["class_reserve_id"],
//   "title"   => $title,
//   "start"   => $row["class_start"],
//   "end"   => $row["class_end"]
//  );
// }
//echo "<pre>";
// var_dump($dataArray);
error_log($query);
// error_log(print_r($dataArray,1));
//echo "</pre>";
// error_log(json_encode($dataArray));
header('Content-Type: application/json');
echo json_encode($dataArray);

?>
