<?php
include 'database.php';
global $conn;
$functionCall='';$functionVal='';
if(!empty($_POST["fid"]) && !empty($_POST["fval"]) )
{
    $functionCall=$_POST["fid"];
    $functionVal=$_POST["fval"];
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
            echo $sql;
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

        // echo $sql;
        $result = mysqli_query($conn, $sql);
        $staffIds = '';
        while($row = mysqli_fetch_array($result))
        {
            $staffIds .= $row["staff"] . ',';
        }
        //echo $staffIds;
        break;
    }
}
?>