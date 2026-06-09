<?php
include 'database-mts.php';
$dataArray = array();
$query = " SELECT * FROM holidays ORDER BY `hid`;";
$result = mysqli_query($conn,$query);
while($row = mysqli_fetch_array($result)) {
    $dataArray[] = array(
      "id" => $row["hid "],
      "title"   => $row["holidayDes"],
      "start"   => $row["startDt"],
      "end"   => $row["enddt"]
     );
}
//echo "<pre>";
//var_dump($dataArray);
//print_r($dataArray);
//echo "</pre>";
echo json_encode($dataArray);

?>