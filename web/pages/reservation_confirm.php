<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
    {
        header('Location:../login.php');
        die();
    }else {

?>
    <?php include 'headtag.php'; ?>
  <!--  <script src="assets/scripts/int_module.js"></script>-->
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>

<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
<?php //include 'assets/scripts/backend/database.php'; ?>
                    <?php
                    if(isset($_POST['confimMod']) ) {
                        echo "aaa";
                        print_r($_POST);
                        extract($_POST);
                        $get_classtimes = "SELECT class_id, class_topic_id, Date(class_start) AS dt, TIME(class_start) AS st, TIME(class_end) AS en, l.lab_code,  lab_nm, class_topic 
                                    FROM  classschedules c
                                    JOIN lab l ON c.lab_code = l.lab_code 
                                    JOIN classtopics 
                                    WHERE class_topic_id =  topic_id 
                                  AND b_no = $bno AND m_code = '$mcode' AND c.lab_code >=1 ORDER BY  class_start" ;
                        echo $get_classtimes;
                        $result = mysqli_query($conn, $get_classtimes);
                        $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule') or die(mysqli_error());
                        while($row = mysqli_fetch_assoc($result)){
                            $classDt = $row['dt'];
                            $classSt = $row['st'];
                            $classEn = $row['en'];
                            $lab = $row['lab_code'];
                            $reason = $row['class_topic'];
                            $classID  = $row['class_id'];
                            echo $row['class_id'] . ' - ' . $row['class_topic_id'] . ' - ' . $row['class_topic'] . ' - ' . $row['dt'] . ' - ' . $row['st'] . ' - ' . $row['en'] . ' - ' . $row['lab_nm'];
                            $sql_lab_availability = "SELECT (CASE WHEN count(*) > 0 THEN 'F' ELSE 'T' END) As ct
                                    FROM reserve 
                                    WHERE lab_code = $lab AND
                                          res_date = '$classDt' AND
                                          st_tm < '$classEn' AND
                                          en_time > '$classSt' AND res_st = 1";
                            $res_lab_availability = mysqli_query($labconn, $sql_lab_availability);
                            $lab_availability = '';
                            if($row = mysqli_fetch_assoc($res_lab_availability)){
                                $lab_availability = $row['ct'];
                            }
                            echo $lab_availability;
                             if($lab_availability == 'T'){
                                 echo "ok";
                                      $usrnm = $_SESSION['userMtsFom'];
                                        $dep1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT dep_code FROM dep WHERE dep_acc = '$mcode'"));
                                        $usrDep = $dep1['dep_code'];
                                               /*  $usr_dep = "SELECT dep_code FROM dep WHERE dep_acc = '$mcode'";
                                                     $re_dep = mysqli_query($conn, $usr_dep) or die(mysqli_error($conn));
                                                  if($row = mysqli_fetch_assoc($re_dep)){
                                                      $usrDep = $row['dep_code'];
                                                  }*/
                                       $ip = $_SERVER['REMOTE_ADDR'];
                                      mysqli_query($labconn,'LOCK TABLES reserve WRITE;');
                                       $sql_add_res = "INSERT INTO  reserve(lab_code, res_dep, res_date, st_tm, en_time, res_by, res_on, res_ip ,res_st, pos, batch, bgroup, reason, remarks, res_source)
                                                   VALUES($lab, $usrDep, '$classDt', '$classSt',  '$classEn' , '$usrnm', now(), '$ip', 1, 1 ,$bno, '$group', '$reason','$remark', 'T'  );";
                                         if(mysqli_query($labconn, $sql_add_res)){
                                             $resID = mysqli_insert_id($labconn);
                                             $sql_updatedate = "UPDATE `classschedules`  SET  res_code = $resID  WHERE  `class_id` = $classID";
                                             mysqli_query($conn,$sql_updatedate);
                                         }  else {
                                             mysqli_error($conn);
                                         }
                                       mysqli_query($labconn,'UNLOCK TABLES reserve;');
                               }else if($lab_availability == 'F'){
                                   echo "<i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"Reject\"></i> " ;
                               }

                            echo "</p>";
                        }
                    }
                    ?>
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Laboratory Reservations </a></li>
            <li class="active breadcrumb-item" aria-current="page">Reservation Confirm</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-refresh-2"></i> <b>List of Reservations to be Confirmed</h2>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th  align="right">BATCH</th>
                        <th  align="center">MODULE NAME</th>
                      <!--  <th  align="center">SUMMARY</th>-->
                        <th  align="center">CONFIRM</th>
                        <th></th>
                    </tr>
                </thead>
				<tbody>
<?php
$sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm
FROM `batchmodule` 
JOIN module m ON m.m_code = batchmodule.m_code
 WHERE  ttprogress IN (2,3) AND 
( cordi IN (SELECT st_id FROM staff WHERE username='$user') OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC; ";
//echo $sql;
database_conectivity();
$result = mysqli_query($conn,$sql);
$i=1;
while($row = mysqli_fetch_array($result)) {
    $bno = $row["b_no"];
    $mcode = $row["m_code"];
    $mname = $row["modnm"];
?>
				<tr id="<?php echo $row["b_no"]; ?>">
					<td align="left"><?php echo $row["b_no"]; ?></td>
					<td><?php echo $row["modnm"]; ?></td>
                  <td>   </td>
                    <td>
                        <a href="#confirmModuleReservations" class="confirmRes" data-toggle="modal"
                           data-rid="<?php echo $row["b_no"]; ?>"
                           data-rid2="<?php echo $row["m_code"]; ?>"
                           data-rid3="<?php echo $row["modnm"]; ?>" >
                            <button class="mb-2 btn btn-warning"><i class="pe-7s-lock" data-toggle="tooltip">Confirm Reservations</i></button>
                        </a>
                    </td>
				</tr>
				<?php
				$i++;
				}
				?>
				</tbody>
			</table>
          </div>
				</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>
</body>
<div id="confirmModuleReservations" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="confirm_form" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Laboratory Reservations Confirmed Report</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="rbno" name="bno" value="<?php echo $bno; ?>" class="form-control">
                    <input type="hidden" id="rmco" name="mcode" value="<?php echo $mcode; ?>" class="form-control" required>
                    <input type="hidden" id="rmnm" name="mnm" value="<?php echo $mname; ?>" class="form-control">
                    <h5><div id="binfo2"></div>
                        Please check the Laboratories details. Please click to confirm.
                    </h5>
                    <?php
                    $get_classtimes = "SELECT class_id, class_topic_id, Date(class_start) AS dt, TIME(class_start) AS st, TIME(class_end) AS en, l.lab_code,  lab_nm, class_topic 
                                    FROM  classschedules c
                                    JOIN lab l ON c.lab_code = l.lab_code 
                                    JOIN classtopics 
                                    WHERE class_topic_id =  topic_id 
                                  AND b_no = $bno AND m_code = '$mcode' AND c.lab_code >=1 ORDER BY  class_start" ;
                   // echo $get_classtimes;
                    $result = mysqli_query($conn, $get_classtimes);
                    $labconn = mysqli_connect('172.18.2.41', 'ransi', '1234', 'labshedule');
                    while($row = mysqli_fetch_assoc($result)){
                        $classDt = $row['dt'];
                        $classSt = $row['st'];
                        $classEn = $row['en'];
                        $lab = $row['lab_code'];
                        $reason = $row['class_topic'];
                        $classID  = $row['class_id'];
                        //echo $row['class_id'] . ' - ' . $row['class_topic_id'] . ' - ' . $row['class_topic'] . ' - ' . $row['dt'] . ' - ' . $row['st'] . ' - ' . $row['en'] . ' - ' . $row['lab_nm'];
                        echo $row['class_topic'] . ' - ' . $row['dt'] . ' - ' . $row['st'] . ' - ' . $row['en'] . ' - ' . $row['lab_nm'];

                        $sql_lab_availability = "SELECT (CASE WHEN count(*) > 0 THEN 'F' ELSE 'T' END) As ct
                                    FROM reserve 
                                    WHERE lab_code = $lab AND
                                          res_date = '$classDt' AND
                                          st_tm < '$classEn' AND
                                          en_time > '$classSt' AND res_st = 1";
                        $res_lab_availability = mysqli_query($labconn, $sql_lab_availability);
                        $lab_availability = '';
                        if($row = mysqli_fetch_assoc($res_lab_availability)){
                            $lab_availability = $row['ct'];
                        }
                        if($lab_availability == 'T'){
                                   echo "<i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"Approve\"></i> " ;
                        }else if($lab_availability == 'F'){
                            echo "<i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"Reject\"></i> " ;
                        }
                        echo "<br>";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="7" name="type">
                    <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-danger" id="confimMod" value="Confirm" name="confimMod">
                </div>
            </form>
        </div>
    </div>
</div>
    <script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
<?php } ?>