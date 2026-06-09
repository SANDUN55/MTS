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
                        </div>
                        <div class="row">
                            <?php
                            $user = $_SESSION["userMtsFom"];
                            include 'assets/scripts/backend/database.php';
                            database_conectivity();
                            $result = mysqli_query($conn, "SELECT CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, 
                                CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  
                                FROM `batchmodule` 
                                JOIN module m ON m.m_code = batchmodule.m_code
                                 WHERE comp_on = '0000-00-00 00:00:00' AND conf_on = '0000-00-00 00:00:00' AND st_dt <> '0000-00-00 00:00:00' AND 	en_dt <> '0000-00-00 00:00:00' AND
                                ( cordi IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
                                 OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC; ");

                            while($row = mysqli_fetch_array($result)) {
                                $val = $row["val"];
                                $val2 = $row["val2"]; ?>
                                <div class="col-sm-3" style="text-align: center" >
                                    <div class="card-shadow-success border mb-3 card card-body border-success">
                                        <h2><i class="pe-7s-date"></i>
                                            <h5 class="card-title"><a href="timetable.php?id=<?php echo $val; ?>">BATCH <?php echo $row ["val2"]; ?> <br> <?php //echo $row ["modnm"]; ?></a></h5>

                                    </div>
                                </div>
                            <?php  }      ?>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Total Orders</div>
                                                <div class="widget-subheading">Last year expenses</div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-success">1896</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <!--
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="card-shadow-danger mb-3 widget-chart widget-chart2 text-left card">
                                    <div class="widget-content">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left pr-2 fsize-1">
                                                    <div class="widget-numbers mt-0 fsize-3 text-danger">71%</div>
                                                </div>
                                                <div class="widget-content-right w-100">
                                                    <div class="progress-bar-xs progress">
                                                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="width: 71%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-content-left fsize-1">
                                                <div class="text-muted opacity-6">Income Target</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>