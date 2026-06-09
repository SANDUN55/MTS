
<?php

//load.php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'faculty');
 
/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$dataArray = array();
$query="SELECT class_reserve_id, class_start, class_end, class_topic, class_group,t_nm, firstname, surname, a_name, lab_nm FROM `classreserve` 
JOIN staff ON classreserve.staff=staff.st_id 
JOIN activity ON a_id=activity 
JOIN lab on lab_code=venue order by `class_reserve_id` ";
$result = mysqli_query($conn,$query);
					
while($row = mysqli_fetch_array($result)) {
	$title=$row["a_name"].' ,\\n '.$row["class_topic"].' -<br>'.$row["t_nm"].' '.$row["firstname"].' \r'. $row["surname"].' - '.$row["lab_nm"];
$dataArray[] = array(
  "id" => $row["class_reserve_id"],
  "title"   => $title,
  "start"   => $row["class_start"],
  "end"   => $row["class_end"],
  "description"=>"Testing Description",
 );
}
//var_dump($dataArray);
//print_r($dataArray);

echo json_encode($dataArray);

?>