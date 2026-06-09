<?php
session_start();
//print_r($_SESSION);
if(!isset($_SESSION["staffMtsFom"]) || $_SESSION["staffLoggedIn"] !== true)
{
    header('Location:login.php');
    die();
}else {
    $okmsg = '';
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
        <div class="app-header header-shadow"  >
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
            <div class="app-header__content" >
             <div class="app-header-left">
             <img src="header.png" />
            </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-right header-user-info ml-3" >
                                    <button type="button" class="btn-shadow btn btn-primary">
                                       <a href="index.php">  <i class="fa text-white fa-home"></i></a>
                                    </button>
                                    <button type="button" class="btn-shadow btn btn-danger">
                                        <a href="logout.php"> <i class="fa text-white fa-sign-out-alt"></i></a>
                                    </button>
                                    <?php
                                    $userID = $_SESSION["staffID"];  
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
                 <img src="home2.jfif" height="275px" width="100%"/>
                 <div class="app-main__inner">
                     <div class="app-page-title">
                         <div class="page-title-wrapper">
                             <div class="page-title-heading">
                                 <div><!--My Dashboard-->
                                     <div class="page-title-subheading">
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="row" style="padding: 20px;background-color:khaki;">
                             <div class="col-sm-12" style="text-align: center" >
                                 <h2>Welcome to Module Timetable System (MTS), Faculty of Medicine, University of Kelaniya </h2>

                             </div>
                         </div>
                         <div class="row" style="padding: 40px;background-color:cornsilk;" >
                             <div class="col-sm-3" style="text-align: center" >
                                 <div class="card-shadow-success border mb-3 card card-body border-success">
                                     <h1><i class="pe-7s-date"></i></h1>
                                     <h3 class="card-title"><a href="myClass.php">MY SCHEDULE</a></h3>

                                 </div>
                             </div>
                             <div class="col-sm-3" style="text-align: center" >
                                 <div class="card-shadow-success border mb-3 card card-body border-success">
                                     <h1><i class="pe-7s-ticket"></i></h1>
                                     <h3 class="card-title"><a href="req_send.php">SEND REQUEST</a></h3>

                                 </div>
                             </div>
                             <div class="col-sm-3" style="text-align: center" >
                                 <div class="card-shadow-success border mb-3 card card-body border-success">
                                     <h1><i class="pe-7s-ribbon"></i></h1>
                                     <h3 class="card-title"><a href="req_check.php">REQUEST STATUS</a></h3>

                                 </div>
                             </div>
                             <div class="col-sm-3" style="text-align: center" >
                                 <div class="card-shadow-success border mb-3 card card-body border-success">
                                     <h1><i class="pe-7s-note2"></i></h1>
                                     <h3 class="card-title"><a href="myMeetingSummary.php">REPORTS</a></h3>

                                 </div>
                             </div>

                         </div>
                     </div>
                 </div>

                 <?php include 'footer.php'; ?>
             </div>
        </div>
    </div>
</body>
</html>
<?php } ?>