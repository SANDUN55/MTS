<?php
include 'database.php';
if(count($_POST)>0){
      // print_r($_POST);
        $type = $_POST['type'];
        $data = explode('&', $_POST['data']);
        $idval = explode('=', $data[0]);
        $ids = explode('-', $idval[1]);
        $tid = $ids[0];
        $cid = $ids[1];
        $repKey = $ids[2];
        $ridval = explode('=', $data[1]);
        $rid = $ridval[1];
        //$repkeyval = explode('=', $data[2]);
        //$repKey = $repkeyval[1];
        $feildval = 'rep' . $repKey . '_comment';
        $sql = "UPDATE `classschedules` SET  $feildval = $type WHERE class_id = $cid AND class_topic_id = $tid";
        //echo $sql;
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "comment class success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "comment class error " . addslashes($sql);
        }

    //mysqli_close($conn);

    //id=494-480&id=1&batch=33&key=3

   /*
    if($_POST['type']==1){
        //$id = $_POST['id'];
        $id = explode('-', $_POST['id']);
        $topicid = $id[0];
        $classid = $id[1];
        $repid = $id[2];
        $repLogged = $id[3];
        $sql = "UPDATE `classschedules` SET  	st_comment  = 1, st_comment_rep=$repLogged WHERE class_id = $classid AND class_topic_id = $topicid";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "comment class success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "comment class error " . addslashes($sql);
        }
        writeLog($logAction);
        //mysqli_close($conn);
    }
    if($_POST['type']==2){
        $id = explode('-', $_POST['id']);
        $topicid = $id[0];
        $classid = $id[1];
        $repid = $id[2];
        $repLogged = $id[3];
        $sql = "UPDATE `classschedules` SET  	st_comment  = 0, st_comment_rep=$repLogged WHERE class_id = $classid AND class_topic_id = $topicid";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode"=>200));
            $logAction = "comment class success " . addslashes($sql);
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $logAction = "comment class error " . addslashes($sql);
        }
        writeLog($logAction);
       // mysqli_close($conn);
    }*/
}
?>