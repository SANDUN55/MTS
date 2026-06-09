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

    <link rel="stylesheet" href="../app/fullcalendar3.4.0.css" />
    <script src="../app/jquery3.2.1.min.js"></script>
    <script src="../app/jquery1.12.1-ui.min.js"></script>
    <script src="../app/moment2.18.1.min.js"></script>
    <script src="../app/fullcalendar3.4.0.min.js"></script>
    <script src="../app/bootstrap3.4.1.min.js"></script>

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
<?php
$batchhMod = array();
$urlData = json_decode( base64_decode( $_GET['md'] ) );
$getVal='';
if($urlData) {
$getVal = $urlData;
$batchhMod = explode('-', $getVal);
}
                                ?><h2 align="center"> Batch <?php  echo $batchhMod[0]. ' - ' . $batchhMod[2]. ' Module' ; ?>  </h2>
                    
				</div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow btn btn-primary">
                                       <a href="../index.php">  <i class="fa text-white fa-home"></i></a>
                                    </button>
                                    <button type="button" class="btn-shadow btn btn-success ">
                                        <a href="../../web/login.php"> <i class="fa text-white fa-sign-in-alt"></i></a>
                                    </button>
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
                 <div class="app-header__menu">asdasdasdasdsad
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                 </div>
                
                 
                 
                 
                 
                 
                 <div class="scrollbar-sidebar">
                  <img src="../sidenav-logo.png" style="margin-top : -10px;"   />
                     <div class="app-sidebar__inner">
                         <ul class="vertical-nav-menu">
                           
<?php
include 'env/database.php';
$sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm, CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val
FROM `batchmodule` 
JOIN module m ON m.m_code = batchmodule.m_code
WHERE comp_on = '0000-00-00 00:00:00' AND conf_on = '0000-00-00 00:00:00' AND ttprogress = 1 ORDER BY b_no, modnm DESC; ";
// echo $sql;
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($result)) {
    $val = $row ["val"];
    $urlVal = base64_encode( json_encode($val) );
 ?>
                                 <li class="app-sidebar__heading"><i class="metismenu-icon pe-7s-date"></i>
                                    <a href="module-calendar.php?md=<?php echo $urlVal; ?>" data-toggle="tooltip" title="<?php echo ' Batch ' . $row ["b_no"] . ' - ' .$row ["modnm"]; ?>">
                                        BATCH <?php echo $row ["b_no"]. ' : ' . $row ["m_code"] ?> </a>
                                 </li>
                                  <?php    }   ?>
                         </ul>
                         <!--
                         <ul class="vertical-nav-menu">
                             <li class="app-sidebar__heading">Dashboards</li>
                            <li> <a href="index.html"> <i class="metismenu-icon pe-7s-rocket"></i> Dashboard Example 1 </a></li>

                         </ul>-->
                     </div>
                 </div>
             </div>
             <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">

                                        <div id='mcalendar'></div>
                                    </div>
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
<script type="text/javascript" src="main.js"></script>
    <script type="text/javascript" src="timetable-calender.js" data-params="<?php echo $getVal; ?>"></script>
</body>
</html>
