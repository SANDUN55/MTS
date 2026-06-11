<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
    $bno = $mcode = $mname = $group = $remark = ''; // Initialize variables
?>
    <?php include 'headtag.php'; ?>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include 'header-top.php'; ?>
        <?php  include 'assets/scripts/backend/select_val.php';?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
                    <?php
                    if(isset($_POST['confimMod']) ) {
                       extract($_POST);
                       unset($_REQUEST["notRL"]);
                       $get_classtimes = "SELECT class_id, class_topic_id, Date(class_start) AS dt, TIME(class_start) AS st, TIME(class_end) AS en, l.lab_code,  lab_nm, class_topic
                                    FROM  classschedules c
                                    JOIN lab l ON c.lab_code = l.lab_code 
                                    JOIN classtopics ON class_topic_id =  topic_id 
                                  WHERE b_no = $bno AND m_code = '$mcode' AND c.lab_code >=1 ORDER BY  class_start" ;
$result = mysqli_query($conn, $get_classtimes);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}                        $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
                        while($row = mysqli_fetch_assoc($result)){
                            $classDt = $row['dt'];
                            $classSt = $row['st'];
                            $classEn = $row['en'];
                            $lab = $row['lab_code'];
                            $reason = $row['class_topic'];
                            $classID  = $row['class_id'];
                            $sql_lab_availability = "SELECT (CASE WHEN count(*) > 0 THEN 'F' ELSE 'T' END) As ct
                                    FROM reserve 
                                    WHERE lab_code = $lab AND
                                          res_date = '$classDt' AND
                                          st_tm < '$classEn' AND
                                          en_time > '$classSt' AND res_st = 1";
                            $res_lab_availability = mysqli_query($labconn, $sql_lab_availability) or die(mysqli_error($labconn));
                            $lab_availability = '';
                            if($row = mysqli_fetch_assoc($res_lab_availability)){
                                $lab_availability = $row['ct'];
                            }
                            if($lab_availability == 'T'){
                                $usrnm = $_SESSION['userMtsFom'];
                                $dep1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT dep_code FROM dep WHERE dep_acc = '$mcode'"));
                                $usrDep = $dep1['dep_code'];
                                $ip = $_SERVER['REMOTE_ADDR'];
                                mysqli_query($labconn,'LOCK TABLES reserve WRITE;');
                                $sql_add_res = "INSERT INTO  reserve(lab_code, res_dep, res_date, st_tm, en_time, res_by, res_on, res_ip ,res_st, pos, batch, bgroup, reason, remarks, res_source)
                                                   VALUES($lab, $usrDep, '$classDt', '$classSt',  '$classEn' , '$usrnm', now(), '$ip', 1, 1 ,$bno, '$group', '$reason','$remark', 'T'  );";
                                if(mysqli_query($labconn, $sql_add_res)){
                                    $resID = mysqli_insert_id($labconn);
                                    $sql_updatedate = "UPDATE `classschedules`  SET  res_code = $resID  WHERE  `class_id` = $classID";
                                    mysqli_query($conn,$sql_updatedate);
                                }  
                                mysqli_query($labconn,'UNLOCK TABLES reserve;');
                            }
                        }
                        echo "<script> alert('Available Laboratories  Reserved, Please check the reports and clarify.'); </script>";
                    //    header("Location:reservation_details.php");
                    }
                    ?>
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Laboratory Reservations </a></li>
            <li class="active breadcrumb-item" aria-current="page">Reserve Labs</li>
        </ol>
    </nav>
    <div class="main-card mb-3 card">
        <div class="card-body"><h2 class="table-title">Reserve Laboratory</h2><h4><div align="center" id="ttstatusdisplay" style="color: mediumvioletred"></div></h4>
            <form class="needs-validation" novalidate id="user_form">
                <div class="position-relative form-group">
                    <label>Select Batch and Module to Reserve the Laboratories :</label>
                    <?php loadBatchModuleMy();?>
                    <input type="hidden" id="ttp">
                </div>
                <button class="mb-2 btn btn-info" name="reserveL" value="1" ><i class="pe-7s-tick" data-toggle="tooltip">Display Laboratory Reserved List</i></button>
                <button class="mb-2 btn btn-warning" name="notRL" value="0"><i class="pe-7s-cross" data-toggle="tooltip">Display Laboratory Not Reserved List</i></button>
            </form>
            <div class="form-row">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th  align="right">DATE</th>
                        <th  align="center">TIME</th>
                        <th  align="center">CLASS</th>
                        <th  align="center">LABORATORY</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
            <?php
            if(isset($_REQUEST["reserveL"]) || isset($_REQUEST["notRL"])) {
                $params = "";
                if (isset($_REQUEST["reserveL"])) {
                    $params = "COALESCE(c.res_code, 0) <> 0";
                } elseif (isset($_REQUEST["notRL"])) {
                    $params = "c.class_start > NOW() AND COALESCE(c.res_code, 0) = 0";
                }

                $selectVal = $_REQUEST["selectBatchMo"] ?? '';
                $str = explode("-", $selectVal);
                $bno = isset($str[0]) ? (int)$str[0] : 0;
$mcode = isset($str[1]) ? mysqli_real_escape_string($conn, trim($str[1])) : '';
                echo "<h3>". htmlspecialchars($selectVal) ."</h3><br>";

                $get_classtimes1 = "
                    SELECT 
                        c.class_id, 
                        c.class_topic_id, 
                        DATE(c.class_start) AS dt, 
                        TIME(c.class_start) AS st, 
                        TIME(c.class_end) AS en, 
                        c.lab_code, 
                        l.lab_nm, 
                        ct.class_topic,
                        c.res_code, 
                        c.class_remark, 
                        a.a_name,
                        IF(ct.dep_code <> ct.staff, 
                           CONCAT(s.t_nm, ' ', s.firstname, ' ', s.surname), 
                           d.div_nm) as val
                    FROM classschedules c 
                    LEFT JOIN lab l ON c.lab_code = l.lab_code 
                    LEFT JOIN classtopics ct ON c.class_topic_id = ct.topic_id 
                    LEFT JOIN activity a ON ct.activity = a.a_id 
                    LEFT JOIN staff s ON ct.staff = s.st_id 
                    LEFT JOIN divisions d ON d.div_id = ct.dep_code 
                    WHERE ct.b_no = $bno 
                      AND TRIM(ct.m_code) = '$mcode' 
                      AND c.class_status = 1 
                      AND $params
                    ORDER BY c.class_start;";

                $result = mysqli_query($conn, $get_classtimes1) or die(mysqli_error($conn));

                if(mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='5' class='text-center text-warning'>No records found for this batch/module.</td></tr>";
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    $classDt = $row['dt'];
                    $reason = $row['class_topic'];
                    $labnm = $row['lab_nm'];
                    $rescode = $row['res_code'];
                    $crem = substr($row['class_remark'], 0, 4);
                    $classSt = $row['st'];
                    $classEn = $row['en'];
                ?>
                    <tr id="<?php echo $row["class_id"]; ?>">
                        <td align="left" width="10%"><?php echo $classDt; ?></td>
                        <td width="15%"><?php echo $classSt . ' - ' . $classEn; ?></td>
                        <td width="50%"><?php echo $row["a_name"] . " - " . $row["val"]; ?></td>
                        <td width="20%"><?php echo $row['lab_nm'];
                            if ($crem == 'http') echo ' (online)';
                            if (empty($labnm)) {
                                echo " Not Entered";
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
            ?>
                    </tbody>
            <?php if (isset($_REQUEST["notRL"]) && $bno > 0 && $mcode != '') { ?>
                <hr>
                <a href="#confirmModuleReservations" class="confirmRes" data-toggle="modal"
                   data-rid="<?php echo $bno; ?>"
                   data-rid2="<?php echo $mcode; ?>"
                   data-rid3="">
                    <button class="mb-2 btn btn-danger"><i class="pe-7s-tick" data-toggle="tooltip">Confirm Reservations</i></button>
                </a>
            <?php } ?>

            <?php   } else { ?>
                    </tbody>
            <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
            </div>
      </div>
</div>
</div>
</body>

<div id="confirmModuleReservations" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="confirm_form"  method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Laboratory Reservations</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="rbno" name="bno" value="<?php echo $bno; ?>" class="form-control">
                    <input type="hidden" id="rmco" name="mcode" value="<?php echo $mcode; ?>" class="form-control" required>
                    <input type="hidden" id="rmnm" name="mname" value="<?php echo $mname; ?>" class="form-control">
                    <h5><div id="binfo2"></div>
                        Please click on the confirm button to confirm the reservations.
                    </h5>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="7" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-danger" id="confimMod" value="Confirm" name="confimMod">
                </div>
            </form>
        </div>
    </div>
</div>
    <script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
<?php } ?>