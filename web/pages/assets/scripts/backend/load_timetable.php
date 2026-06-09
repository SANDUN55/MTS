<?php
include 'database.php';
$bno = $_GET['custom_param1'];
$mod = $_GET['custom_param2'];
$status = $_GET['custom_param3'];
//$bno = 30;
//$mod = 'ALNU1';
$dataArray = array();
database_conectivity();
//20/12/2023 APPLIED COLOR TO DEPARTMENTS
/*$query = " SELECT CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, CONCAT(t_nm, '. ', firstname, ' ', surname, ' ') AS stVAL , a_name, a_type, lab_nm, class_remark
 FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN staff ON classtopics.staff = staff.st_id
JOIN activity ON a_id = activity
LEFT JOIN lab on lab.lab_code = classschedules.lab_code
WHERE classtopics.dep_code <> classtopics.staff AND class_status = $status AND b_no = $bno AND m_code = '$mod'
ORDER BY `class_id`;";*/


// $query = " SELECT CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, CONCAT(t_nm, '. ', firstname, ' ', surname, ' ') AS stVAL , a_name, a_type, lab_nm, class_remark, div_color 
//  FROM `classschedules`
//  JOIN classtopics ON class_topic_id = topic_id
// LEFT JOIN staff ON classtopics.staff = staff.st_id
// LEFT JOIN divisions ON staff.dep_code = div_id
// JOIN activity ON a_id = activity
// LEFT JOIN lab on lab.lab_code = classschedules.lab_code
// WHERE classtopics.dep_code <> classtopics.staff AND class_status = $status AND b_no = $bno AND m_code = '$mod'
// ORDER BY `class_id`;";
$file = fopen("example3.txt", "w+");

$query = "SELECT 
    CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
    class_topic, 
    class_start, 
    class_end, 
    classtopics_staff.class_group, 
    GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname, ' ', staff.surname) SEPARATOR ', ') AS stVAL,
    a_name, 
    a_type, 
    lab_nm, 
    class_remark, 
    COALESCE(div_color, '#2E7D32') as div_color 
FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics.topic_id
LEFT JOIN staff ON classtopics_staff.staff = staff.st_id
LEFT JOIN divisions ON staff.dep_code = div_id
JOIN activity ON a_id = activity
LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
WHERE class_status = $status AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')
GROUP BY class_id, class_topic_id
ORDER BY class_id;";

// fwrite($file, $query);

$query .= "SELECT 
    CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
    class_topic, 
    class_start, 
    class_end, 
    classtopics_staff.class_group as class_group,
    GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname, ' ', staff.surname) SEPARATOR ', ') AS stVAL,
    div_nm, 
    a_name, 
    a_type, 
    lab_nm, 
    class_remark, 
    COALESCE(div_color, '#2E7D32') as div_color
FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics.topic_id
LEFT JOIN staff ON classtopics_staff.staff = staff.st_id
LEFT JOIN divisions ON div_id = dep_code 
JOIN activity ON a_id = activity
LEFT JOIN lab on lab.lab_code = classschedules.lab_code
WHERE classtopics.dep_code = classtopics.staff  AND class_status = $status AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')
GROUP BY class_id, class_topic_id
ORDER BY `class_id`;";

fwrite($file, $query);
// echo $query;
fclose($file);
//if (mysqli_multi_query($conn, $query)) {
//    do {
//        if ($result = mysqli_store_result($conn)) {
//            while ($row = mysqli_fetch_assoc($result)) {
//

//                $file = fopen("example2.txt", "a");
//                fwrite($file, print_r($row, true));
//                fclose($file);

                //$title = $row["a_name"].' , "'.$row["class_topic"].'" ';
 //               $title = ''.stripslashes($row["class_topic"]).' ';
 //               $activityType = $row["a_type"];
 //               $classGroup = '';
 //               if ($activityType == 'G') $classGroup = $row["class_group"];
 //               elseif ($activityType == 'N') $classGroup = '';
 //               $TitleDescription = stripslashes( $classGroup) . ' : ' . $row["stVAL"].$row["div_nm"].' : '.$row["lab_nm"].': '.stripslashes($row["class_remark"]) ;
 //               $description = $row["a_name"] . ',' . stripslashes($row["class_remark"]);
                
                
                
 //               $dataArray[] = array(
 //                   "id" => $row["class_reserve_id"],
 //                   "title"   => $title.': '.$TitleDescription,
 //                   "start"   => $row["class_start"],
 //                   "end"   => $row["class_end"],
 //                  "description" => $description,
//		   "color"=> $row["div_color"],
//		//    "feedbackURL"=> $row["feedback"]
 //               );
 //           }
 //           mysqli_free_result($result);
 //       }
 //   } while (mysqli_next_result($conn));
//}
/*
echo "<pre>";
print_r($dataArray);
echo "</pre>";

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
	//echo json_encode($dataArray);
	//
	//
	//
$lecturersByClass = [];
$eventRows = [];

// Debug: Log the query and parameters
$debug_file = fopen("debug_query.txt", "w+");
fwrite($debug_file, "Batch: $bno, Module: '$mod', Status: $status\n");
fwrite($debug_file, "Query: $query\n");

// Test query to see if there are any confirmed classes
$test_query = "SELECT COUNT(*) as count FROM classschedules 
               JOIN classtopics ON class_topic_id = topic_id
               WHERE class_status = $status AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')";
$test_result = mysqli_query($conn, $test_query);
$test_row = mysqli_fetch_assoc($test_result);
fwrite($debug_file, "Found {$test_row['count']} classes with status $status\n");

// Also check for confirmed classes specifically
$confirmed_query = "SELECT COUNT(*) as count FROM classschedules 
                     JOIN classtopics ON class_topic_id = topic_id
                     WHERE class_status = 1 AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')";
$confirmed_result = mysqli_query($conn, $confirmed_query);
$confirmed_row = mysqli_fetch_assoc($confirmed_result);
fwrite($debug_file, "Found {$confirmed_row['count']} confirmed classes (status=1)\n");

fclose($debug_file);

if (mysqli_multi_query($conn, $query)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $eventId = $row["class_reserve_id"];
                // Collect lecturer names for each class
                if (!isset($lecturersByClass[$eventId])) {
                    $lecturersByClass[$eventId] = [];
                    $eventRows[$eventId] = $row; // Save row for later
                }
                $lecturersByClass[$eventId][] = isset($row["stVAL"]) ? $row["stVAL"] : 'No lecturer assigned';
            }
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
} else {
    // Debug: Log SQL error
    $error_file = fopen("debug_error.txt", "w+");
    fwrite($error_file, "SQL Error: " . mysqli_error($conn) . "\n");
    fclose($error_file);
}

$uniqueEvents = [];
foreach ($eventRows as $eventId => $row) {
    $topic = ''.stripslashes($row["class_topic"]).' ';
    $activityType = $row["a_type"];
    // $classGroup = ($activityType == 'G') ? $row["class_group"] : '';
    // $classGroup = '';
                if ($activityType == 'G') $classGroup = $row["class_group"];
                elseif ($activityType == 'N') $classGroup = '';
     $lecturers = (isset($row["stVAL"]) && trim($row["stVAL"]) !== '') ? $row["stVAL"] : 'No lecturer assigned';
     $TitleDescription = stripslashes($classGroup) . ' : ' . $lecturers . (isset($row["div_nm"]) ? $row["div_nm"] : '') . ' : ' . $row["lab_nm"] .  ' : ' . stripslashes($row["class_remark"]);
                $description = $row["a_name"] . ',' . stripslashes($row["class_remark"]);
                 $q=fopen("o.txt","w+");
                fwrite($q, $TitleDescription);
                fwrite($q, "\n");
                fwrite($q, print_r($row, true));
                fclose($q);
    $venue = !empty($row["lab_nm"]) ? $row["lab_nm"] : 'Online/Not Set';
    $uniqueEvents[$eventId] = array(
        "id"          => $eventId,
        "title"       => $row["a_name"] . "\n" . $topic . "\n" . $lecturers . "\n📍 " . $venue,
        "start"       => $row["class_start"],
        "end"         => $row["class_end"],
        "description" => $topic . ' | Venue: ' . $venue . ' | ' . $TitleDescription, // Full details in description
        "color"       => $row["div_color"], // Use color from database (with green fallback)
    );
}
echo json_encode(array_values($uniqueEvents));
?>
