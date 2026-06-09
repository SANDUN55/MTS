<?php
session_start();
//print_r($_SESSION);
if(!isset($_SESSION["staffMtsFom"]) || $_SESSION["staffLoggedIn"] !== true)
{
    header('Location:login.php');
    die();
}else {
    $okmsg = '';
        include 'database.php';
        if(isset($_POST['btn-add-req'])){
           // print_r($_POST);
            $classid = $_POST['cid'];
            $topicid = $_POST['tip'];
            $classDate = $_POST['classDate'];
            $classStTime = $_POST['classStTime'];
            $classEnTime = $_POST['classEnTime'];
            $classStTime = $classDate . ' ' . $classStTime;
            $classEnTime = $classDate . ' ' . $classEnTime;
            $reqUid = $_POST['reqU'];
            $batch = $_POST['bno'];
            $mod = $_POST['$mcode'];
            $sql = "INSERT INTO `classsreq` (`class_id`, `class_topic_id`, `class_start`, `class_end`, `add_staff`) 
        VALUES ( $classid, $topicid, '$classStTime', '$classEnTime', '$reqUid');";
            //echo $sql;
            if (mysqli_query($conn, $sql)) {
                $okmsg = 'Request send success';
                $usrnm =  $_SESSION["staffMtsFom"];
                $sqlfr = "SELECT st_em, CONCAT (t_nm, ' ', firstname, ' ', surname ) as staffNm, div_nm  FROM staff WHERE username = '$usrnm';";
                $resfrom = mysqli_query($conn, $sqlfr);
                if($row = mysqli_fetch_assoc($resfrom)){
                    $seml =  $row['st_em'];
                    $stnm =  $row['staffNm'];
                    $dvnm =  $row['div_nm'];
                }
                $sqltopic = "SELECT class_topic, a_name  FROM classtopics JOIN activity  ON activity = a_id WHERE topic_id = $topicid;";
                $restp = mysqli_query($conn, $sqltopic);
                if($row = mysqli_fetch_assoc($restp)){
                    $topicnm =  $row['class_topic'];
                    $actnm =  $row['a_name'];
                }
                $sqld = "SELECT cordi, st_em, m_name FROM batchmodule JOIN staff ON st_id = cordi JOIN module ON module.m_code = batchmodule.m_code WHERE b_no  = $batch AND batchmodule.m_code = '$mod';";
                $resd = mysqli_query($conn, $sqld);
                if($row = mysqli_fetch_assoc($resd)){
                    $seml =  $row['st_em'];
                    $modnm =  $row['m_name'];
                }
                $to = $seml ;
                $subject = "Batch " . $batch . $modnm . " request time change - MTS, Faculty of Medicine";
                $message = "Dear Module convener, <br><br>
Please consider the following change to the timetable.<br>
Session - ". $actnm . " - " . $topicnm  ."<br>
Many Thanks!<br><br>" . $stnm . "<br>" . $dvnm . "<br>
This is an automatically generated email <br><br>";
                $headers = 'From: "Module Timetable System" <medu@kln.ac.lk>'. "\r\n" ;
                $headers .= "Content-Type: text/html;";
                mail($to, $subject, $message, $headers,"-f gayathri@kln.ac.lk");
            }
            else {
                $okmsg = 'Something went wrong, please try again';
            }
        }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Module Timetable System (MTS), Faculty of Medicine, University of Kelaniya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="author" content="W GYATAHRI H">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->

    <script src="../app/assets/jquery3.2.1.min.js"></script>
    <script src="../app/assets/jquery1.12.1-ui.min.js"></script>
    <script src="../app/assets/moment2.18.1.min.js"></script>
    <script src="../app/assets/bootstrap3.4.1.min.js"></script>
<link href="../web/pages/main.css" rel="stylesheet">
<style>
    .okmsg{
        /*color:#E21842;*/
        color:mediumblue;
        background: #E7F92B;
        display:inline;
        position:relative;
        padding-left:10px;
        top:0px;
        -webkit-animation:fade-in 2s ease-in-out;
        animation-delay: 4s;
        -webkit-animation-fill-mode: forwards;
    }
</style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__content">
             <div class="app-header-left">
             <img src="header.png" />
            </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow btn btn-primary">
                                       <a href="index.php">  <i class="fa text-white fa-home"></i></a>
                                    </button>
                                    <button type="button" class="btn-shadow btn btn-danger">
                                        <a href="logout.php"> <i class="fa text-white fa-sign-out-alt"></i></a>
                                    </button>
                                    <?php
                                    $userID = $_SESSION["staffID"];  //echo $userID;
                                      ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="app-main">
             <?php include 'navbar.php'; ?>
             <div class="app-main__outer">
                 <?php include 'ribbon.php'; ?>
                        <div class="card-body">
                            <div class="container">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h2><!--<i class="fas fa-calendar-plus icon-gradient bg-plum-plate"></i>--> <b> Timetable  Class Changes Request Form</b></h2>
                                                <div class="okmsg"><?php echo $okmsg; ?></div>
                                            </div>
                                        </div>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th align="center">BATCH - MODULE</th>
                                                        <th>DATE</th>
                                                        <th align="center">TIME</th>
                                                        <th align="center">TOPIC</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $result = mysqli_query($conn,"SELECT class_id, class_topic_id, class_topic, class_start, class_end, b_no,  m_code, a_name, lab_nm FROM `classschedules`
                                                 JOIN classtopics ON class_topic_id = topic_id
                                                LEFT JOIN staff ON classtopics.staff = staff.st_id
                                                JOIN activity ON a_id = activity
                                                LEFT JOIN lab on lab.lab_code = classschedules.lab_code
                                                WHERE classtopics.dep_code <> classtopics.staff AND class_status = 1 AND staff = $userID AND 
                                                NOW() < class_start   AND CONCAT(b_no, m_code)  IN
                                                (SELECT concat( b_no, m_code ) FROM batchmodule WHERE ttprogress = 2 or ttprogress = 3  ) 
                                                ORDER BY  `class_start`");
                                                    $i=1;
                                                while($row = mysqli_fetch_array($result)) {
                                                    $phpdate = strtotime( $row["class_start"] );
                                                    $dt = date( 'Y-m-d', $phpdate );
                                                    $stTm = date( 'H:i', $phpdate );
                                                    $phpdate = strtotime( $row["class_end"] );
                                                    $enTm = date( 'H:i', $phpdate );
                                                    $des = $dt . ' ' . $stTm . ' - ' . $enTm ;
                                                    $bno = $row["b_no"];
                                                    $mcode = $row["m_code"];
                                                ?>
                                                <tr id="<?php echo $row["b_no"]; ?>">
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row["b_no"].' - '.$row["m_code"]; ?></td>
                                                    <td><?php echo $dt; ?></td>
                                                    <td><?php echo $stTm . ' to '. $enTm; ?></td>
                                                    <td align="left"><?php echo $row["a_name"]. " : " . $row["class_topic"]; ?></td>
                                                    <td>
                                                        <a href="#addReq" class="updatett" data-toggle="modal" data-id1="<?php echo $row["class_id"]; ?>"
                                                           data-id2="<?php echo $row["class_topic_id"]; ?>"
                                                           data-ds="<?php echo $des; ?>">
                                                            <i class="pe-7s-angle-right-circle icon-gradient bg-malibu-beach" data-toggle="tooltip" title="Request"> </i> send
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
                 <?php include 'footer.php'; ?>
             </div>
        </div>
    </div>
    <script>
        $("#addRepModal").on("hidden.bs.modal",function(){
            $('#user_rep_form')[0].reset();
        });
        $(document).on('click','.updatett',function(e) {
            var id1=$(this).attr("data-id1");
            var id2=$(this).attr("data-id2");
            var des=$(this).attr("data-ds");
            $('#id_c').val(id1);
            $('#id_t').val(id2);
            //  $('#mn_u').val(des);
            $('#mn_u').text(des);
        });
        /*  $(document).on('click','#btn-add-req',function(e) {

         var valid = this.form.checkValidity();
         if(valid) {
         event.preventDefault();
         var data = $("#req_form").serialize();
         alert(data);
         $.ajax({
         data: data,
         type: "post",
         url: "assets/scripts/backend/registries.php",
         success: function (dataResult) {
         var dataResult = JSON.parse(dataResult);
         if (dataResult.statusCode == 200) {
         $('#editHolModal').modal('hide');
         alert('Data updated successfully !');
         location.reload();
         }
         else if (dataResult.statusCode == 201) {
         alert(dataResult);
         }
         }
         });
         }else{
         $("#update_form")[0].reportValidity();
         }
         });*/

    </script>
<!-- Add Modal HTML -->
<div id="addReq" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="needs-validation" novalidate id="req_form" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Request Change to Module Convenor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <label><h5> <div id="mn_u"></div> Change to : </h5></label>
                    <fieldset class="border p-2">
                        <legend  class="w-auto"></legend>
                        <div class="position-relative form-group">
                            <label>Date</label>
                            <input type="date" id="date1" name="classDate"   class="form-control" required >
                            <div class="invalid-feedback">
                                Please provide a valid date.
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" id="start" name="classStTime"  class="form-control" min="07:00" max="17:00" required>
                                <div class="invalid-feedback">
                                    Please provide a valid time.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" id="endtm" name="classEnTime"  class="form-control" min="07:00" max="17:00" required>
                                <div class="invalid-feedback">
                                    Please provide a valid time.
                                </div>
                            </div>
                        </div>
                        <br>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="6" name="type">
                    <input type="hidden" value="<?php echo $userID;  ?>" name="reqU">
                    <input type="hidden" name="cid" id="id_c">
                    <input type="hidden" name="tip" id="id_t">
                    <input type="hidden" name="bno" id="bno" value="<?php echo $bno; ?>">
                    <input type="hidden" name="mcode" id="mcode" value="<?php echo $mcode; ?>">
                    <input type="button" class="btn btn-light" data-dismiss="modal" value="Close">
                    <button type="submit" class="btn btn-success" id="btn-add-req" name="btn-add-req">Send Request</button>
                </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="../app/assets/main.js"></script>
</body>
</html>
<?php } ?>