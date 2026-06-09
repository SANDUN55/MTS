
<?php
// include 'env/database.php';
include '../../myApp/databaseEduty.php';
$bno = $_GET['custom_param1'];

$dataArray = array();
$o=fopen("query.txt","w+");

$query = "call getExamSchedule();";

if (mysqli_multi_query($conn3, $query)) {
    do {
        /* store first result set */
        if ($result = mysqli_store_result($conn3)) {
            while ($row = mysqli_fetch_assoc($result)) {
                //$title=$row["a_name"].'<br>'.$row["class_topic"].'-'.$row["t_nm"].' - '.$row["firstname"].' \r'. $row["surname"].' - '.$row["div_nm"].'-'.$row["lab_nm"];
                // $title=$row["m_code"].' ' .$row["a_name"].' : '.$row["class_topic"].' : '.$row["class_group"];
                // $description=$row["stVAL"].$row["div_nm"].' : '.$row["lab_nm"];

            // $date = (new DateTime($row["examEnd"]))->format('Y-m-d');
            // fwrite($o,$date);
                $title=$row["title"];
                $description=$row["paperNo"].$row["hallName"];
                // $value = $row["examId"];
                // $exam = trim($value, "/");

                $dataArray[] = array(
                    "id" =>  $row["examId"],
                    "title"   => $title.' : '.$description,
                    "start"   => $row["examDate"],
                    "end"   => $row["examEnd"],
                    // "color" => $row["div_color"],
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
    } while (mysqli_next_result($conn3));
    fclose($o);
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