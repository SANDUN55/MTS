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
                                    <a href="index.php"><i class="fa text-white fa-home"></i></a>
                                </button>
                                <button type="button" class="btn-shadow btn btn-danger">
                                    <a href="logout.php"><i class="fa text-white fa-sign-out-alt"></i></a>
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
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="container">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2><b>Lectures Schedule in Tentative/Confirmed Timetables</b></h2>
                                        <div class="okmsg"><?php echo $okmsg ?? ''; ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Form -->
                            <form method="post" action="">
                                <div class="row mt-2 mb-3">
                                    <div class="col-md-4">
                                        <label for="startDate">Start Date:</label>
                                        <input type="date" name="startDate" id="startDate" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="endDate">End Date:</label>
                                        <input type="date" name="endDate" id="endDate" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <?php
                            if (isset($_POST["filter"])) {
                                $startDate = $_POST["startDate"];
                                $endDate = $_POST["endDate"];
                                // Add DB config here
                                $sql1 = "SELECT st_em FROM staff WHERE st_id = $userID";
                                $staffEmail = mysqli_fetch_array(mysqli_query($conn, $sql1))["st_em"];
                                ?>

                                <div style="display: flex; gap: 50px;">
                                    <div><b>Start Date</b>: <?php echo $startDate; ?></div>
                                    <div><b>End Date</b>: <?php echo $endDate; ?></div>
                                </div>

                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th align="center">BATCH - MODULE</th>
                                        <th>DATE</th>
                                        <th align="center">TIME</th>
                                        <th align="center">TOPIC</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "CALL ch_lecHoursList('$startDate','$endDate','$staffEmail');";
                                    $result = mysqli_query($conn, $sql);
                                    $i = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                        $phpdate = strtotime($row["class_start"]);
                                        $dt = date('Y-m-d', $phpdate);
                                        $stTm = date('H:i', $phpdate);
                                        $phpdate = strtotime($row["class_end"]);
                                        $enTm = date('H:i', $phpdate);
                                        ?>
                                        <tr id="<?php echo $row["b_no"]; ?>">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row["b_no"] . ' - ' . $row["m_code"]; ?></td>
                                            <td><?php echo $dt; ?></td>
                                            <td><?php echo $stTm . ' to ' . $enTm; ?></td>
                                            <td align="left"><?php echo $row["activity_name"] . " : " . $row["class_topic"]; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
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
