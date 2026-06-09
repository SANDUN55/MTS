<?php
session_start();
//print_r($_SESSION);
if(!isset($_SESSION["staffMtsFom"]) || $_SESSION["staffLoggedIn"] !== true)
{
    header('Location:login.php');
    die();
}else {
    include 'databaseEduty.php';
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
                                    $userID = $_SESSION["staffID"];            //                        echo $userID;
                                      print_r($_SESSION);
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
                     <div class="app-main__inner">
                         <div class="row">
                             <div class="col">
                                 <nav class="" aria-label="breadcrumb">
                                     <ol class="breadcrumb">
                                         <li class="breadcrumb-item"><a href="registry_home.php">My Reports</a></li>
                                         <li class="active breadcrumb-item" aria-current="page">Meeting Summary</li>
                                     </ol>
                                 </nav>
                             </div>
                         </div>
                         <form class="needs-validation" novalidate id="user_form">
                             <div class="row">
                                 <div class="col">
                                     <div class="main-card mb-3 card">
                                         <div class="card-body" style="height: 100%"><h5 class="card-title">Meeting Summary Reports</h5>
                                             <!--form-->
                                             <fieldset class="border p-2">
                                                 <legend  class="w-auto"></legend>
                                                 <div class="position-relative form-group">
                                                 </div>
                                                    <?php
                                                    //call getExamierData('%');
                                                    // call getExamierData('2977');
                                                    $user = $_SESSION['staffMtsFom'];
                                                    $sql = "SELECT m.m_id, div_nm, m.div_id, SEC_TO_TIME(SUM(TIME_TO_SEC(end_time) - TIME_TO_SEC(start_time))) AS TimeDif 
                                                                FROM `attendance` att 
                                                                JOIN staff sf ON sf.st_id= att.st_id 
                                                                JOIN meetings m ON m.m_id = att.m_id
                                                                JOIN divisions d on d.div_id = m.div_id
                                                                WHERE sf.username = '$user' AND status = 'p'
                                                                GROUP BY  m.div_id;";
                                                    echo $sql;
                                                    $res = mysqli_query($conn2, $sql);
                                                    ?>
                                                 <div id="printDoc">
                                                     <h3 id="head1"><?php //echo $bno . ' - ' . $module; ?></h3>
                                                     <table class="table table-striped table-hover">
                                                         <thead>
                                                         <tr>
                                                             <th align="center">MEETING NAME</th>
                                                             <th align="center">TOTAL TIME (H:M)</th>
                                                         </tr>
                                                         </thead>
                                                         <tbody>
                                                         <?php
                                                         while ($row = mysqli_fetch_assoc($res)) {
                                                             echo "<tr>";
                                                             echo "<td>" . $row["div_nm"] . "</td>";
                                                             echo "<td align=\"right\">" . substr($row["TimeDif"], 0, -3) . "</td>";
                                                             echo "</tr>";
                                                         }
                                                         ?>
                </tbody>
            </table>
        </div>
                                             </fieldset>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </form>
                     </div>
             </div>
         </div>
</div>

                 <?php include 'footer.php'; ?>
             </div>
        </div>
    </div>
<script type="text/javascript" src="../app/assets/main.js"></script>
</body>
</html>
<?php } ?>