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
    <script src="timetable.js"></script>

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
        <?php include 'navbar.php'; ?>
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="container">
                            <?php
                            $batchhMod = array(); $getVal='';
                            if($_GET['id']) {
                                $getVal = $_GET['id'];
                                $batchhMod = explode('-', $getVal);
                            }
                            ?>
                            <?php if($_SESSION["cat"] == 6) { ?>
                                <h2 align="center"> Batch <?php  echo $batchhMod[0]. ' - ' . $batchhMod[2]. ' Module' ; ?>  </h2>
                            <?php } ?>
                            <div id="calendar">
                        </div>
                        </div>
                        <div>
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
<script src="timetableMy-calender.js" data-params="<?php echo $getVal. ' - '. $userID; ?>" ></script>
</html>
