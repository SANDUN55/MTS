<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Module Timetable System (MTS), FoM</title>
    <meta name="viewport" content=
    "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="author" content="W GYATAHRI H">
    <meta name="msapplication-tap-highlight" content="no"><!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
    <link rel="icon" href="../web/pages/assets/images/favicon.png" sizes="32x32">
    <link rel="icon" href="assets/images/favicon.png" sizes="192x192">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">
    <meta name="msapplication-TileImage" content=    "assets/images/favicon.png">
    <link href="../web/pages/main.css" rel="stylesheet">
    <script src="../web/pages/assets/scripts/jquery1.12.4.min.js"> </script>
    <script src="../web/pages/assets/scripts/bootstrap3.3.7.min.js"> </script>
    <style>
        /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
        .row.content {
            height: auto;
            }

        /* Set gray background color and 100% height */
        .sidenav {
            background-image: url("bg1.jpg") ;
            height: 850px;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }
        .anm{
            color: #002a80;
            font-size: 1.3em;
            white-space: nowrap;
            overflow: hidden;
            width: 150%;

            animation: animtext 4s steps(80, end);
            transition: all cubic-bezier(0.1, 0.7, 1.0, 0.1);
        }
        @keyframes animtext {
            from {
                width: 0;
                transition: all 2s ease-in-out;
            }
        }
        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height: auto;}
        }
    </style>
</head>
<!--head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
        .row.content {height: 1500px}

        /* Set gray background color and 100% height */
        .sidenav {
            background-color: #f1f1f1;
            height: 100%;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height: auto;}
        }
    </style>
</head-->
<body style="background-color:cornsilk;">

<div class="container-fluid">
    <div class="row content">

        <div class="col-sm-3 sidenav">
          <!--      <h4></h4>
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#section1">Home</a></li>
                    <li><a href="#section2">Friends</a></li>
                    <li><a href="#section3">Family</a></li>
                    <li><a href="#section3">Photos</a></li>
                </ul><br>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search Blog..">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                        </button>
                      </span>
                </div>-->
        </div>

        <div class="col-sm-9">
            <!-- <img src="Full_logo.jpg" style="text-align: center; "> -->
            <div align ='center'>
                <button type="button" class="btn btn-primary"><a href="../myApp/login.php" class="text-white"> <i class="fa text-white fa-user-plus"></i> Staff Portal</a>  </button>
                <button type="button" class="btn btn-info "> <a href="../reps/login.php" class="text-white"> <i class="fa text-white fa-user-plus"></i> Reps Portal</a>  </button>
                <button type="button" class="btn btn-success"> <a href="../web/login.php" class="text-white"> <i class="fa text-white fa-user-plus"></i> Convenor Portal</a></button>
            </div>
            <div style="float: left;">
                <img src="coatofarms.png" width="90px" >
            </div>
	        <div style="float: right;">
                <img src="logo.png" width="90px">
            </div>
		
            <h2 style="text-align: center"> <!--WELCOME TO FACULTY OF MEDICINE, UNIVERSITY OF KELANIYA-->
                    Welcome to Faculty of Medicine, University of Kelaniya
            </h2>

            <h3 style="text-align: center; background-color : khaki;"> MBBS Module Timetables</h3>
            <h6 style="text-align: center;"><span class="glyphicon glyphicon-time"></span> Today,  <?php date_default_timezone_set('UTC');echo  date('l jS \of F Y ');?></h6>

           <!-- <p style="font-size: 1rem;text-align:justify;">The academic course starts with a Foundation Module. During the next four terms students learn about the normal structure and function of the human body through 8 self-contained, organ-systembased modules (Phase I).
             In Phase II (3rd and 4th years), students concentrate on the acquisition of clinical skills and on learning about the disease conditions that affect humans. </p>
-->
            <p class="anm"> Shaping Future Healers. Navigate Your Path to Excellence...</p>
            <hr> <b>Please click on the Module to display the timetable.</b>
<br>
            <div class="row">
 <?php
 include 'assets/env/database.php';
 //WHERE comp_on = '0000-00-00 00:00:00' AND conf_on = '0000-00-00 00:00:00'
 $sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm, CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, 
                          CONCAT( t_nm, '. ' , firstname , ' ', surname) as val2
                    FROM `batchmodule` 
                    JOIN module m ON m.m_code = batchmodule.m_code
                    JOIN staff on cordi = st_id
             WHERE ttprogress = 3 AND en_dt > now() ORDER BY b_no DESC , modnm ASC; ";
              // echo $sql;
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($result)) {
    $val = $row ["val"];
    $urlVal = base64_encode( json_encode($val) );
                ?>
                <div class="col-sm-3" style="text-align: center" >
                    <div class="card-shadow-success border mb-3 card card-body border-success">
                        <h2><i class="pe-7s-date"></i>
                        <h5 class="card-title"><a href="assets/module-calendar.php?md=<?php echo $urlVal; ?>">BATCH <?php echo $row ["b_no"]; ?> <br> <?php echo $row ["modnm"]; ?></a> </h5>
                            <h6><em><?php echo $row ["val2"]; ?></em></h6>
                    </div>
                </div>
<?php  }      ?>
            </div>

            <div class="row">
                <h4>&nbsp Master Timetable</h4>
            </div>
            <div class="row">
                <?php
                $sql = "SELECT b_no, b_current_year  FROM `batch` WHERE  batchstatus = 1 ORDER BY b_no DESC; ";
                // echo $sql;
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_array($result)) {
                    $val = $row ["b_no"];
                    $urlVal = base64_encode( json_encode($val) );
                    ?>
                    <div class="col-sm-2" style="text-align: center" >
                        <div class="card-shadow-success border mb-3 card card-body border-success">
                            <h2><i class="pe-7s-date"></i>
                                <h5 class="card-title"><a href="assets/batch-calendar.php?bt=<?php echo $urlVal; ?>">BATCH
                                        <?php echo $row ["b_no"]; ?> <br> Year <?php echo $row ["b_current_year"]; ?></a></h5>

                        </div>
                    </div>
                <?php  }      ?>
                
            </div>
            <div class="row">
    <h4>&nbsp Exams</h4>
</div>
<div class="row">
    <div class="col-sm-3" style="text-align: center">
        <div class="card-shadow-success border mb-3 card card-body border-primary">
            <h2><i class="pe-7s-note2"></i></h2>
            <h5 class="card-title">
                <a href="assets/exam-calendar.php">
                    Exam Details
                </a>
            </h5>
            <h6><em>Click to view exam information</em></h6>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<footer class="container-fluid" style="text-align: center; color : white">
  <a href="../app/index.php"> <i class="fa text-white fa-home"></i></a> 
  | <a href="../app/index.php"> <i class="fa text-white fa-user-plus"></i></a>   
|  <a href="../app/index.php"> <i class="fa text-white fa-info-circle"></i></a>
    &nbsp; <p>&copy; <?php echo date('Y'); ?>, Health Data Science Unit (HDSU), Faculty of Medicine. University of Kelaniya.  All Rights Reserved</p>

</footer>

</body>
</html>
