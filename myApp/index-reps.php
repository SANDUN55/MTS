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

    <link rel="stylesheet" href="../app/assets/fullcalendar3.4.0.css" />
    <script src="../app/assets/jquery3.2.1.min.js"></script>
    <script src="../app/assets/jquery1.12.1-ui.min.js"></script>
    <script src="../app/assets/moment2.18.1.min.js"></script>
    <script src="../app/assets/fullcalendar3.4.0.min.js"></script>
    <script src="../app/assets/bootstrap3.4.1.min.js"></script>

<link href="../web/pages/main.css" rel="stylesheet">
    <script src="script.js"></script>
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
  <?php session_start(); $userID = $_SESSION["staffID"];  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="app-main">
             <div class="app-sidebar sidebar-shadow">
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
  
                 <div class="scrollbar-sidebar">
                     <div class="app-sidebar__inner">
                         <ul class="vertical-nav-menu">
                         <li class="app-sidebar__heading"></li>  
                            <li> <a href="index.php"> <i class="metismenu-icon pe-7s-rocket"></i> Dashboard </a></li>
                         </ul>
                     </div>
                 </div>
             </div>
             <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-wallet icon-gradient bg-mean-fruit"></i>
                                    </div>
                                    <div><h5>My Dashboard</h5>
                                       <!-- <div class="page-title-subheading"><h5>The dashboard will loads the classes details to add comments.</h5>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="page-title-actions">
<?php
include 'database.php';
database_conectivity();
$sql = "SELECT CONCAT (st_no, '. ', st_nm ) AS nm, rep_id, b_no FROM batchreps WHERE net_id  LIKE '$userID' AND st_status = 1 ";
//echo $sql;
$repid = $bno = '';
$result = mysqli_query($conn, $sql) or die(mysqli_error());
if($row = mysqli_fetch_array($result)) {
   echo "<h5>" . $row['nm'] . "</h5>" ;
    $repid = $row['rep_id'];
    $bno = $row['b_no'];
}
$rep2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT rep_id FROM batchreps  WHERE b_no = $bno AND rep_id <> $repid AND st_status = 1"));
$rep2id = $rep2['rep_id'];
$loggedrep = 0;
if($repid>$rep2id){
    $loggedrep  = 2;
} else {
    $loggedrep = 1;
}
?>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="container">

                                <div class="table-wrapper" >

                                    <div class="table-title">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h6><b>Click on the row to comment</b></h6>
                                            </div>
                                        </div>
                                    </div>
                                          <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th  align="center">DATE/TIME</th>
                                                        <th  align="center">CLASS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php
//get the batch
$repBatch = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(`b_no`) FROM `batchreps` WHERE `rep_id` between startDt AND enddt"));
//get the class details
$sql = "SELECT topic_id, class_id, m_code, a_name, class_topic, DATE(class_start) as dt , TIME (class_start) as sttm, TIME (class_end) as entm, CONCAT (t_nm, \" \", firstname, \" \", surname) nm, div_nm
FROM classschedules 
LEFT JOIN classtopics ON class_topic_id = topic_id 
JOIN activity ON activity  = a_id
JOIN staff ON staff = st_id
WHERE b_no = 33 AND class_status = 1  ";
//ND (st_comment_rep <> 1 AND st_comment2_rep <>1 )
echo $sql;
/*AND  rep$loggedrep = 0 ";*/
$result = mysqli_query($conn, $sql);
$i=1;
while($row = mysqli_fetch_array($result)) {
    $sttm = strtotime($row["sttm"]);
    $entm = strtotime($row["entm"]);
    $data = $row["topic_id"] . '-' . $row["class_id"] . '-' . $repid . '-' . $loggedrep;
?>
                                                <tr id="<?php echo $i; ?>" class="edit" data-toggle="modal" data-id="<?php echo $data; ?>" data-target="#commentModal" >
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row["dt"]. '<br>' . date( 'h:i', $sttm) .' - ' . date( 'h:i a', $entm); ?></td>
                                                    <td><?php echo $row["a_name"] . ' - ' . $row["class_topic"] . '<br>' . $row["nm"] . ' (' . $row["div_nm"] . ')'  ; ?></td>
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
                   <div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">
                                </div>
                                <div class="app-footer-right">
                                    <p>© 2020, CHIBE, Faculty of Medicine. University of Kelaniya. All Rights Reserved</p>
                                </div>
                            </div>
                        </div>
                    </div>
             </div>
        </div>
    </div>
<script type="text/javascript" src="../app/assets/main.js"></script>
</body>
<div id="commentModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title">Comment on Class</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" id="id_d" name="id" class="form-control">
                    <p style="text-align: center">
                            <button type="button" class="btn btn-success" id="conduct">Conduct</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-danger" id="notconduct">Not-Conduct</button></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">

                </div>
            </form>
        </div>
    </div>
</div>
</html>
