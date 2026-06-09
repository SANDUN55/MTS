<?php
include 'database.php';
global $conn;
$functionCall='';$functionVal='';
if(!empty($_POST["fid"]) && !empty($_POST["fval"]) )
{
    $functionCall = $_POST["fid"];
    $functionVal = $_POST["fval"];
    database_conectivity();
    switch ($functionCall) {
        case 1:
            //$result = mysqli_query($conn,"SELECT m.m_code, m.m_name, m.m_phase FROM module m WHERE m_st=1 AND m_code  NOT IN (SELECT m_code FROM batchmodule WHERE b_no=$functionVal)
                                           // ORDER BY m_phase,m_code ASC;");
            $result = mysqli_query($conn,"SELECT m.m_code, m.m_name, m.m_phase FROM module m WHERE m_st=1  ORDER BY m_phase,m_code ASC;");
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row["m_code"] . '">' . $row["m_name"].', Phase'.$row["m_phase"] . '</option>';
            }
            break;
        case 2:
            $vals = explode(',', $functionVal);
            $bno = $vals[0];
            $mod = $vals[1];
            $sql = "SELECT `st_dt`, `en_dt` FROM `batchmodule` WHERE `b_no` = $bno	AND `m_code` = '$mod'";
            $result = mysqli_query($conn,$sql);
            if ($row=mysqli_fetch_assoc($result)) {
                //$returnVal = $row["st_dt"]. ','. $row["en_dt"];
               echo  $row["st_dt"]. ','. $row["en_dt"];
                //echo json_encode(array("responseText"=>$returnVal));
            }
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            break;
        case 3:
            $vals = explode(',', $functionVal);
            $bno = $vals[0];
            $mod = $vals[1];
            $sql = "SELECT a_id, a_name, activity, count(*) AS ct FROM classschedules 
              JOIN classtopics ON class_topic_id = topic_id JOIN activity ON activity = a_id WHERE b_no = $bno AND m_code = '$mod' AND class_status = 1 GROUP BY activity ORDER BY a_name ";
            // echo $sql;
            $result = mysqli_query($conn,$sql);
            $str = "";
             while($row = mysqli_fetch_array($result))
            {
                $str .= '<tr><td>'.$row["a_name"].'</td>';
                $str .= '<td>'.$row["ct"].'</td></tr>';
            }
            echo $str;
            break;
        case 4:
            $vals = explode(',', $functionVal);
            $bno = $vals[0];
            $mod = $vals[1];
            $activityCode = $vals[2];
            $atype = $actno = '';
            $sql = "SELECT a_type FROM activity WHERE a_id = $activityCode";
            $result = mysqli_query($conn,$sql);
            if($row = mysqli_fetch_array($result))
            {
                $atype = $row["a_type"];
                if($atype == 'G'){
                    $sql2 = "SELECT IFNULL ((MAX(activity_no)+1), 1) AS acno FROM classtopics WHERE b_no=$bno AND m_code='$mod' AND activity=$activityCode";
                    $result2 = mysqli_query($conn,$sql2);
                    if($row2 = mysqli_fetch_array($result2))
                    {
                        $actno = $row2['acno'];
                    }

                }elseif($atype == 'N'){
                    $actno = 0;
                }
            }
            echo $atype . ',' . $actno;
            break;
        case 5:
            $vals = explode('-', $functionVal);
            $bno = $vals[0];
            $mod = $vals[1];
            $sql = "SELECT st_dt ,  en_dt  FROM batchmodule WHERE b_no = $bno AND  m_code = '$mod' ";
            //echo $sql;
            $result = mysqli_query($conn,$sql);
            if($row = mysqli_fetch_array($result))
            {
                echo $row['st_dt'] . ',' . $row['en_dt'];
            }
            break;
        case 6:
        //  2-10,2020-12-10T09:15:00,2020-12-10T10:45:00
        //$functionVal = '2020-12-17T08:30:00,2020-12-17T09:45:00';
        $vals = explode(',', $functionVal);
        //print_r($vals);
        $st1 = $vals[0];//  2020-12-10T10:30:00
        $en1 = $vals[1];//  2020-12-10T11:45:00
        $classStTime = date ('Y-m-d H:i:s', strtotime($st1));
        $classEnTime = date ('Y-m-d H:i:s', strtotime($en1));

        $sql = "SELECT DISTINCT CONCAT(dep_code,'-',staff) as staff  FROM classschedules
                    JOIN classtopics ON class_topic_id = topic_id
                WHERE
                 (class_start < '$classStTime' AND class_end > '$classStTime') OR
                 (class_start < '$classEnTime' AND class_end > '$classEnTime') OR
                 (class_start >= '$classStTime' AND class_end <= '$classEnTime')";

        //echo $sql;
        $result = mysqli_query($conn, $sql);
        $staffIds = '';
        while($row = mysqli_fetch_array($result))
        {
            $staffIds .= $row["staff"] . ',';
        }
        echo $staffIds;
        break;
        case 7:
            $vals = explode('-', $functionVal);
            $bno = $vals[0];
            $mod = $vals[1];
            $query = " SELECT class_id , CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname, ' ', staff.surname) SEPARATOR ', ') AS stVAL, a_name, a_type FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN staff ON classtopics.staff = staff.st_id
JOIN activity ON a_id = activity
WHERE classtopics.dep_code <> classtopics.staff AND class_status IN (0,3) AND b_no = $bno AND m_code = '$mod' AND class_start<=now()
ORDER BY class_start;";
            $query .= " SELECT class_id , CONCAT (class_id, '-', class_topic_id) as class_reserve_id, class_topic, class_start, class_end, class_group, GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname, ' ', staff.surname) SEPARATOR ', ') AS stVAL , a_name, a_type FROM `classschedules`
 JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN staff ON classtopics.staff = staff.st_id
JOIN activity ON a_id = activity
WHERE classtopics.dep_code <> classtopics.staff AND class_status = 1 AND b_no = $bno AND m_code = '$mod' AND class_start>now()
ORDER BY class_start;";

           // $result = mysqli_query($conn, $query);
            if (mysqli_multi_query($conn, $query)) {
                do {
                    if ($result = mysqli_store_result($conn)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $phpdate = strtotime($row['class_start']);
                            $startTm = date('Y-m-d H:i', $phpdate);
                            $phpdate = strtotime($row['class_end']);
                            $endTm = date('H:i', $phpdate);
                            echo '<option value="' . $row["class_reserve_id"] . '">' . $row["class_start"] . ' - ' . $endTm . ' ' . $row["class_topic"] . ' - ' . $row["stVAL"] . '</option>';
                        }
                        mysqli_free_result($result);
                    }
                }while (mysqli_next_result($conn));
                }
            break;
        case 8:
            $result = mysqli_query($conn,"SELECT rep_id, st_no, st_nm FROM batchreps WHERE b_no = $functionVal   ORDER BY st_no ASC;");
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row["rep_id"] . '">' . $row["st_no"].' - '.$row["st_nm"] . '</option>';
            }
            break;
        case 9:
            $we=fopen("j.txt","w+");
            fwrite($we,"SELECT st_id, CONCAT (t_nm, ' ', firstname, ' ', surname ) as staffNm, div_nm  FROM staff WHERE st_cat IN (2, 3,6)  AND dep_code  = $functionVal ORDER BY div_nm, firstname ASC;");
            fclose($we);
            $result = mysqli_query($conn,"SELECT st_id, CONCAT (t_nm, ' ', firstname, ' ', surname ) as staffNm, div_nm  FROM staff WHERE st_cat IN (2, 3,6)  AND dep_code  = $functionVal ORDER BY div_nm, firstname ASC;");
            while($row = mysqli_fetch_array($result)) {
                echo '<option value="' . $row["st_id"] . '">' . $row["staffNm"]. '</option>';
            }
            break;
        case 10:
            //add_class.php->check availability of the selected lab
            $vals = explode(',', $functionVal);
            $lab = $vals[0];
            $classDt = $vals[1];
            $classSt = $vals[2];
            $classEn = $vals[3];
            $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
                $sql_lab_availability = "SELECT (CASE WHEN count(*) > 0 THEN 'F' ELSE 'T' END) As ct
                                    FROM reserve 
                                    WHERE lab_code = $lab AND
                                          res_date = '$classDt' AND
                                          st_tm < '$classEn' AND
                                          en_time > '$classSt' AND res_st = 1";
               // echo $sql_lab_availability;
                $labSt = mysqli_fetch_assoc(mysqli_query($labconn, $sql_lab_availability));
                if($labSt['ct'])
                    $lst = $labSt['ct'];
                echo $lst;
                /*if($lst == 'F')
                    echo 'N';*/

            break;
        case 11:
            //add_class.php->get timetable status
            $vals = explode('-', $functionVal);
            $bno = $vals[0];
            $mod = $vals[1];
            $ttStatus = ''; $stext = '';
            $ttStatusGet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ttprogress  FROM batchmodule  WHERE `b_no` = $bno AND `m_code` = '$mod'"));
            if($ttStatusGet['ttprogress']) {
                $ttStatus = $ttStatusGet['ttprogress'];
            }
            if($ttStatus==1)
                $stext =  "Draft Timetable";
            elseif($ttStatus==2)
                $stext = "Tentative Timetable";
            elseif($ttStatus==3)
                $stext =  "Confirmed Timetable";
            echo $stext;
            break;

    }
}
?>