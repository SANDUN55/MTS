<?php
include 'env/database.php';

header('Content-Type: application/json');

/* ================= INPUT ================= */
$bno = $_GET['custom_param1'] ?? '';
$mod = $_GET['custom_param2'] ?? '';

// For backward compatibility / testing
if (empty($bno) && isset($_GET['batch'])) {
    $parts = preg_split('/[\s-]+/', trim($_GET['batch']));
    $bno = $parts[0] ?? '';
    $mod = $parts[1] ?? $mod;
}

$bno = mysqli_real_escape_string($conn, $bno);
$mod = mysqli_real_escape_string($conn, $mod);

/* ================= REMOTE DB CONNECTION ================= */
$conn2 = new mysqli("172.18.2.41", "ransi", "1234", "labshedule");

if ($conn2->connect_error) {
    error_log("Remote DB connection failed: " . $conn2->connect_error);
    // You can choose to continue without filter or exit
    // echo json_encode(["error" => "Remote DB connection failed"]); exit;
}

/* ================= GET ACTIVE RESERVATIONS ================= */
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
}

/* ================= MAIN QUERIES (Same as your old logic) ================= */
$dataArray = [];

$query = "
    SELECT 
        CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
        class_topic, class_start, class_end, class_group, 
        CONCAT(t_nm, '. ', firstname, ' ', surname, ' ') AS stVAL,
        a_name, lab_nm, class_remark, div_color,
        classschedules.lab_code,
        b_no
    FROM `classschedules`
    JOIN classtopics ON class_topic_id = topic_id
    LEFT JOIN staff ON classtopics.staff = staff.st_id
    LEFT JOIN divisions ON staff.dep_code = div_id
    JOIN activity ON a_id = activity
    LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
    WHERE classtopics.dep_code <> classtopics.staff 
      AND class_status = 1 
      AND b_no = '$bno' 
      AND m_code = '$mod'
    ORDER BY class_id;

    SELECT 
        CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
        class_topic, class_start, class_end, class_group, 
        div_nm, a_name, lab_nm, class_remark, div_color,
        classschedules.lab_code,
        b_no
    FROM `classschedules`
    JOIN classtopics ON class_topic_id = topic_id
    LEFT JOIN divisions ON div_id = dep_code 
    JOIN activity ON a_id = activity
    LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
    WHERE classtopics.dep_code = classtopics.staff  
      AND class_status = 1 
      AND b_no = '$bno' 
      AND m_code = '$mod'
    ORDER BY class_id;
";

if (mysqli_multi_query($conn, $query)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            while ($row = mysqli_fetch_assoc($result)) {

                /* ================= RESERVATION FILTER ================= */
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
                    continue;   // Skip if not reserved
                }

                /* ================= BUILD TITLE (Preserving your old logic) ================= */
                $title = $row["a_name"] . ' : ' . $row["class_topic"];
                if (!empty($row['class_group'])) {
                    $title .= ' : ' . $row['class_group'];
                }

                $description = !empty($row['stVAL']) ? $row['stVAL'] : $row['div_nm'] ?? '';
                $description .= ' : ' . $row['lab_nm'];

                $fullTitle = $title . ' : ' . $description . (!empty($row['class_remark']) ? ', ' . stripslashes($row['class_remark']) : '');

                $dataArray[] = [
                    "id"      => $row["class_reserve_id"],
                    "title"   => $fullTitle,
                    "start"   => $row["class_start"],
                    "end"     => $row["class_end"],
                    "color"   => $row["div_color"] ?? '#3788d8'
                ];
            }
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
}

$conn2->close();

/* ================= OUTPUT ================= */
echo json_encode($dataArray, JSON_PRETTY_PRINT);
?>