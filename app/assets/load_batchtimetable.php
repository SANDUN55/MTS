<?php
include 'env/database.php';

header('Content-Type: application/json');

/* ================= INPUT ================= */
$bno_raw = $_GET['batch'] ?? $_GET['custom_param1'] ?? '';

if (empty($bno_raw) && isset($_GET['batch'])) {
    $parts = preg_split('/[\s-]+/', trim($_GET['batch']));
    $bno_raw = $parts[0] ?? '';
}

$bno = mysqli_real_escape_string($conn, trim($bno_raw));

if (empty($bno)) {
    echo json_encode(["error" => "Batch number is required", "events" => [], "count" => 0]);
    exit;
}

/* ================= REMOTE DB - Reservations ================= */
$conn2 = new mysqli("172.18.2.41", "ransi", "1234", "labshedule");
$reservations = [];

if (!$conn2->connect_error) {
    $res_query = "SELECT lab_code, batch, res_date FROM reserve WHERE res_st = 1";
    $res_result = $conn2->query($res_query);

    if ($res_result) {
        while ($row = $res_result->fetch_assoc()) {
            $reservations[] = $row;
        }
        $res_result->free();
    }
    $conn2->close();
}

/* ================= MAIN QUERY ================= */
$dataArray = [];

$query = "
    SELECT 
        CONCAT(class_id, '-', class_topic_id) as id, 
        class_topic, class_start, class_end, class_group, 
        CONCAT(t_nm, '. ', firstname, ' ', surname) AS stVAL,
        a_name, lab_nm, class_remark, 
        div_color,
        classschedules.lab_code
    FROM `classschedules`
    JOIN classtopics ON class_topic_id = topic_id
    LEFT JOIN staff ON classtopics.staff = staff.st_id
    LEFT JOIN divisions ON staff.dep_code = div_id
    JOIN activity ON a_id = activity
    LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
    WHERE classtopics.dep_code <> classtopics.staff 
      AND class_status = 1 
      AND b_no = '$bno'

    UNION ALL

    SELECT 
        CONCAT(class_id, '-', class_topic_id) as id, 
        class_topic, class_start, class_end, class_group, 
        div_nm as stVAL,
        a_name, lab_nm, class_remark, 
         div_color,
        classschedules.lab_code
    FROM `classschedules`
    JOIN classtopics ON class_topic_id = topic_id
    LEFT JOIN divisions ON classtopics.dep_code = div_id
    JOIN activity ON a_id = activity
    LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
    WHERE classtopics.dep_code = classtopics.staff  
      AND class_status = 1 
      AND b_no = '$bno'
    
    ORDER BY class_start;
";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        /* Reservation Filter - Only show if lab is reserved */
        $classDate = date('Y-m-d', strtotime($row['class_start']));
        $isReserved = false;

        foreach ($reservations as $r) {
            if (
                $r['lab_code'] == $row['lab_code'] &&
                $r['batch'] == $bno &&
                $r['res_date'] == $classDate
            ) {
                $isReserved = true;
                break;
            }
        }

        if (!$isReserved) {
            continue;
        }

        /* Build Title */
        $title = $row['a_name'] . ' : ' . $row['class_topic'];
        if (!empty($row['class_group'])) {
            $title .= ' (' . $row['class_group'] . ')';
        }

        $staff_lab = (!empty($row['stVAL']) ? trim($row['stVAL']) : '') .
                     (!empty($row['lab_nm']) ? ' | Lab: ' . $row['lab_nm'] : '');

        $remark = !empty($row['class_remark']) ? ', ' . stripslashes($row['class_remark']) : '';

        $fullTitle = $title . ' - ' . $staff_lab . $remark;

        /* Event with Strong Color Support */
        $colorCode = trim($row['div_color']);

        $dataArray[] = [
            "id"              => $row['id'],
            "title"           => $fullTitle,
            "start"           => $row['class_start'],
            "end"             => $row['class_end'],
            "backgroundColor" => trim($row['div_color']),
            "borderColor"     => trim($row['div_color']),
            "color"           => trim($row['div_color'])
        ];
    }
    mysqli_free_result($result);
}

/* ================= OUTPUT ================= */
echo json_encode([
    "events" => $dataArray,
    "count"  => count($dataArray),
    "batch"  => $bno
], JSON_PRETTY_PRINT);
?>