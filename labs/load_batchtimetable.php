
<?php
header('Content-Type: application/json');
include 'database.php';

$lab = isset($_GET['custom_param1']) ? intval($_GET['custom_param1']) : 0;
$dataArray = array();

if ($lab > 0) {
    $query = "SELECT res_id, dep_nm, CONCAT(res_date, ' ', st_tm  ) AS class_start , CONCAT(res_date, ' ', en_time  ) AS class_end, reason, batch, pos_name, bgroup  
              FROM reserve
              JOIN pos ON reserve.pos = pos.pos_code
              JOIN dep ON res_dep = dep_code
              WHERE lab_code = $lab AND res_st = 1 
              ORDER BY res_date, st_tm";
    
    $result = mysqli_query($labconn, $query);
    
    if ($result === false) {
        error_log("Lab calendar query failed: " . mysqli_error($labconn) . " Query: " . $query);
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row["pos_name"] . ' ' . $row["batch"] . ' : ' . $row["reason"];
            $description = $row["dep_nm"];
            $dataArray[] = array(
                "id" => $row["res_id"],
                "title" => $title . ' : ' . $description,
                "start" => $row["class_start"],
                "end" => $row["class_end"],
            );
        }
    }
}

echo json_encode($dataArray);

?>