<?php
session_start();
//print_r($_SESSION);
if(!isset($_SESSION["staffMtsFom"]) || $_SESSION["staffLoggedIn"] !== true)
{
    header('Location:login.php');
    die();
}else {
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
                                     $userID = $_SESSION["staffID"];                            //        echo $userID;
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
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="container">
                                <div class="table-wrapper" >
                                    <div class="table-title">
                                        <div class="row">

                                            <div class="col-sm-8">
                                                <h2><i class="fas fa- icon-gradient bg-plum-plate"></i> <b> Class Changes Request Status</b></h2>
                                            </div>
                                        </div>
                                    </div>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th  align="center">BATCH - MODULE</th>
                                                        <th>TOPIC</th>
                                                        <th  align="center">SCHEDULE</th>
                                                        <th  align="center">REQUEST</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sql = "SELECT r.req_id, r.`class_id`, r.`class_topic_id`, t.b_no, t.m_code, t.activity, a.a_name , t.class_topic, t.class_group , r.`class_start` AS rclass_start, r.`class_end`AS rclass_end, 
                                                        s.class_start AS oclass_st, s.class_end AS oclass_en, r.req_status
                                                        FROM classsreq r
                                                        JOIN classtopics t ON r.class_topic_id = t.topic_id 
                                                        JOIN classschedules s ON r.class_id= s.class_id 
                                                        JOIN activity a ON t.activity = a.a_id
                                                        WHERE r.add_staff = $userID ORDER BY r.add_dt DESC;";
                                                $result = mysqli_query($conn, $sql);
                                                $i=1;
                                                while($row = mysqli_fetch_array($result)) {
                                                    $phpdate = strtotime($row["oclass_st"] );
                                                    $dt = date( 'Y-m-d H:i', $phpdate );
                                                    $phpdate = strtotime( $row["oclass_en"] );
                                                    $enTm = date( 'H:i', $phpdate );
                                                    $des = $dt . ' - ' . $enTm ;
                                                    $phpdate = strtotime($row["rclass_start"] );
                                                    $dtr = date( 'Y-m-d H:i', $phpdate );
                                                    $phpdate = strtotime( $row["rclass_end"] );
                                                    $enTmr = date( 'H:i', $phpdate );
                                                    $desr = $dtr . ' - ' . $enTmr ;
                                                    $rst = $row['req_status'];
                                                    ?>
                                                    <tr id="<?php echo $row["req_id"]; ?>">
                                                        <td><?php echo $i; ?></td>
                                                        <td align="center"><?php echo $row["b_no"]. ' - ' .$row["m_code"] ; ?></td>
                                                        <td align="left"><?php echo $row["a_name"]. ' : ' .$row["class_topic"] ; ?></td>
                                                        <td><?php echo $des; ?></td>
                                                        <td align="left"><?php echo $desr; ?></td>
                                                        <td align="center"><?php
                                                            switch ($rst) {
                                                                case 0:
                                                                    //echo "pending";
                                                                    echo "<div class=\"font-icon-wrapper\"><i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"Pending\"></i>" ;
                                                                    break;
                                                                case 1:
                                                                    //echo "reject";
                                                                    echo "<div class=\"font-icon-wrapper\"><i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"Reject\"></i> </div>" ;
                                                                    break;
                                                                case 2:
                                                                    //echo "approve";
                                                                    echo " <div class=\"font-icon-wrapper\"><i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"Approve\"></i>  </div>" ;
                                                                   
                                                                    break;
                                                            }

                                                        ?>
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
                    <input type="hidden" value="6" name="cid" id="id_c">
                    <input type="hidden" value="6" name="tip" id="id_t">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-success" id="btn-add-req" name="btn-add-req">Add Request</button>
                </div>
        </div>
    </div>
</div>
<script>
    $("#addRepModal").on("hidden.bs.modal",function(){
        $('#user_rep_form')[0].reset();
    });
     $(document).on('click','.updatett',function(e) {
         //alert("ok");
         var id1=$(this).attr("data-id1");
         var id2=$(this).attr("data-id2");
         var des=$(this).attr("data-ds");
         $('#id_c').val(id1);
         $('#id_t').val(id2);
         $('#mn_u').val(des);
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

<script type="text/javascript" src="../app/assets/main.js"></script>
</body>
</html>
<?php } ?>