<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true){
    {
        header('Location:../login.php');
        die();
    }
}else {
    ?>
    <?php include 'headtag.php'; ?>
    <body >
    <?php include 'header-top.php'; ?>
    <?php include 'layout.php'; ?>
    <div class="app-main">
        <?php include 'navbar-left.php'; ?>
        <div class="app-main__outer">
            <?php //include 'home.php'; ?>
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <img src="assets/images/dashboard-image.jpg" height="200px" width="100%" >
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
                    <div class="row" style="padding: 20px;background-color:#939ba7;">
                        <div class="col-sm-12" style="text-align: center" >
                            <?php
                                $sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm, ttprogress, (CASE WHEN NOW() BETWEEN st_dt AND en_dt  THEN 'T' ELSE 'F' END) As ct
                                FROM `batchmodule` 
                                JOIN module m ON m.m_code = batchmodule.m_code
                                 WHERE  ttprogress <=3 AND 
                                ( cordi IN (SELECT st_id FROM staff WHERE username='$user') OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user'))
                                     OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
                                 ORDER BY b_no DESC; ";
                                database_conectivity();
                                $result = mysqli_query($conn,$sql);
                                 while($row = mysqli_fetch_array($result)) {
                                     $bno = $row["b_no"];
                                     $mcode = $row["modnm"];
                                     $ttp = $row["ttprogress"];
                                     $ct = $row["ct"];
                                 }
                                 $style = ' progress-bar-animated progress-bar-striped ';
                                ?>
                            <?php if($ttp>0) { ?>
                                <h4>BATCH <?php echo $bno . ' - ' . $mcode;  ?></h4>
                                <div class="progress">
                                    <div class="progress-bar <?php if($ttp==1) echo $style; ?>" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">DRAFTING</div>
                                    <div class="progress-bar bg-success <?php if($ttp==2) echo $style; ?>" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">PUBLISH TENTATIVE</div>
                                    <div class="progress-bar bg-info <?php if($ttp==3) echo $style; ?> " role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">TIMETABLE CONFIRMED</div>
                                    <div class="progress-bar bg-warning <?php if($ttp==4) echo $style; ?> " role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">RESERVATION CONFIRMED</div>
                                    <div class="progress-bar bg-danger <?php if($ttp==4 && $ct=='T') echo $style; ?> " role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">ON GOING</div>
                                </div>
                                <?php } ?>

                        </div>
                    </div>
    <?php if($_SESSION["cat"]==4) { ?>
                    <div class="row" style="padding: 40px;background-color:cornsilk;" >
                        <div class="col-sm-2" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-users"></i></h1>
                                    <h3 class="card-title"><a href="#">USER MANAGEMENT</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-2" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-settings"></i></h1>
                                    <h3 class="card-title"><a href="#">MODULE MANAGEMENT</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-2" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-ribbon"></i></h1>
                                    <h3 class="card-title"><a href="#">CLASS MANAGEMENT</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-2" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-date"></i></h1>
                                    <h3 class="card-title"><a href="#">TIMETABLE MANAGEMENT</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-2" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-note2"></i></h1>
                                    <h3 class="card-title"><a href="#">REPORTS</a></h3>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

  <?php if($_SESSION["cat"]==3) { ?>
                    <div class="row">
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h2><i class="pe-7s-ba"></i>
                                    <h5 class="card-title"><a href="registry_batch.php">BATCH  <br><br>
                                            <img src="assets/images/crud2.png" width="100%">
                                        </a>
                                    </h5>
                                </h2>
                            </div>
                        </div>
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h2><i class="pe-7s-ba"></i>
                                    <h5 class="card-title"><a href="registry_module.php">MODULE  <br><br>
                                            <img src="assets/images/crud2.png" width="100%">
                                        </a>
                                    </h5>
                                </h2>
                            </div>
                        </div>
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h2><i class="pe-7s-ba"></i>
                                    <h5 class="card-title"><a href="registry_holidays.php">HOLIDAYS  <br><br>
                                            <img src="assets/images/crud2.png" width="100%">
                                        </a>
                                    </h5>
                                </h2>
                            </div>
                        </div>
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h2><i class="pe-7s-ba"></i>
                                    <h5 class="card-title"><a href="#">USERS  <br><br>
                                            <img src="assets/images/crud2.png" width="100%">
                                        </a>
                                    </h5>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 40px;background-color:cornsilk;" >
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-users"></i></h1>
                                <h3 class="card-title"><a href="registry_batchreps.php">ADD BATCH REPRESENTATIVE</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-date"></i></h1>
                                <h3 class="card-title"><a href="registry_holidays.php">MANAGE HOLIDAYS</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-user"></i></h1>
                                <h3 class="card-title"><a href="init_module.php">MANAGE MODULE CONVENER</a></h3>
                            </div>
                        </div>
                        <div class="col-sm-3" style="text-align: center" >
                            <div class="card-shadow-success border mb-3 card card-body border-success">
                                <h1><i class="pe-7s-users"></i></h1>
                                <h3 class="card-title"><a href="registry_batchrepModule.php">ASSIGN REPS TO MODULES</a></h3>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            <?php  define('MtsFom', true); ?>
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script type="text/javascript" src="./assets/scripts/main.js"></script>


    </body>
    </html>
    <?php
}
?>