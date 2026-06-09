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
                                <?php session_start();

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
                        <?php
                        include '../web/pages/assets/scripts/backend/database.php';
                        $sql = "SELECT CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, 
CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  
FROM `batchmodule` 
JOIN module m ON m.m_code = batchmodule.m_code
WHERE NOW() BETWEEN st_dt AND en_dt AND ttprogress = 1 AND CONCAT(b_no, m.m_code) 
IN (SELECT concat( b_no, m_code ) FROM classtopics WHERE dep_code != staff AND staff = $userID ); ";
                        //echo $sql;
                        $result = mysqli_query($conn, $sql) or die(mysqli_error());
                        while($row = mysqli_fetch_array($result)) {
                            $val = $row["val"];
                            $val2 = $row["val2"];
                            ?>
                            <li><a href="timetableMy.php?id=<?php echo $val; ?>"><i class="metismenu-icon"></i><?php echo $val2; ?></a></li>
                        <?php }     ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="app-main__outer">
            <div class="app-main__inner">



                
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
</html>
