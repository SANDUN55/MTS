                <div class="app-sidebar sidebar-shadow bg-night-sky sidebar-text-light">
                    <div class="app-header__logo">
                        <!--div class="logo-src"></div-->
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
                    <div class="app-header__menu">
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                    </div>    <div class="scrollbar-sidebar">
                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu">
                             <!--   <li class="app-sidebar__heading">Dashboards</li> -->
                         <!--       <li>
                                    <a href="index.php" class="mm-active"><i class="metismenu-icon pe-7s-science"></i>Main Dashboard</a>
                                </li> -->
                                <li>
                                    <a href="index.php" class="mm-active"><i class="metismenu-icon pe-7s-science"></i>DASHBOARD</a>
                                </li>
                                <li>
                                    <a href="../logout.php" class="mm-active"><i class="metismenu-icon pe-7s-right-arrow"></i>LOGOUT</a>
                                </li>
                                <?php

                                ?>
                                <?php if($_SESSION["cat"]<=3) { ?>
                                <li class="app-sidebar__heading">Administration</li>
                                <li><a href="registry_home.php"><i class="metismenu-icon pe-7s-settings"></i>Registries
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li><a href="registry_batch.php"><i class="metismenu-icon"></i>Batch</a></li>
                                        <li><a href="registry_phase.php"><i class="metismenu-icon"></i>Phase</a></li>
                                        <li><a href="registry_strand.php"><i class="metismenu-icon"></i>Strand</a></li>
                                        <li><a href="registry_module.php"><i class="metismenu-icon"></i>Module</a></li>
                                        <li><a href="registry_holidays.php"><i class="metismenu-icon"></i>Holidays</a></li>
                                        <li><a href="registry_batchreps.php"><i class="metismenu-icon"></i>Batch Representatives  </a></li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if($_SESSION["cat"]==2 || $_SESSION["cat"]==4) { ?>
                                <li><a href="registry_home.php"><i class="metismenu-icon pe-7s-users"></i>User Management
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <?php if($_SESSION["cat"]<=2) { ?>
                                        <li><a href="registry_user.php"><i class="metismenu-icon"></i>System  Users </a></li>
                                        <?php }
                                         if($_SESSION["cat"]==1 || $_SESSION["cat"]==2 || $_SESSION["cat"]==4) { ?>
                                        <li><a href="registry_userTT.php"><i class="metismenu-icon"></i>Timetable Managers </a></li>
                                        <li><a href="registry_visitingStaff.php"><i class="metismenu-icon"></i>Visiting Staff </a></li>
                                        <?php }
                                         ?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if($_SESSION["cat"]<=3) { ?>
                                <li>
                                    <a href="#"><i class="metismenu-icon pe-7s-users"></i>Module Management
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li><a href="init_module.php"><i class="metismenu-icon"></i>Manage Module Convener</a></li>
                                        <li><a href="registry_batchrepModule.php"><i class="metismenu-icon"></i>Assign Reps to Module </a></li>
                                       <!-- <li><a href="registry_holidays.php"><i class="metismenu-icon"></i>Holidays</a></li>-->
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if($_SESSION["cat"]==4 ) { ?>
                                    <li>
                                        <a href="#"><i class="metismenu-icon pe-7s-settings"></i>Module Management
                                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                        </a>
                                        <ul>
                                            <li><a href="start_module.php"><i class="metismenu-icon"></i>Define Module</a></li>
                                            <li><a href="registry_classreq.php"><i class="metismenu-icon"></i>Check Requests</a></li>
                                            <li><a href="start_module.php"><i class="metismenu-icon"></i>Complete Module</a></li>
                                            <li><a href="history-module-calendar.php"><i class="metismenu-icon"></i>Previous Batch</a></li>
                                        </ul>
                                    </li>
                                <li>
                                    <a href="#"><i class="metismenu-icon pe-7s-ribbon"></i>Class Management
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <!--li><a href="init_class.php"><i class="metismenu-icon"></i>Define Class</a></li-->
                                        <!-- <li><a href="registry_class.php"><i class="metismenu-icon"></i>Class Registry</a></li>-->
                                        <li><a href="add_class.php"><i class="metismenu-icon"></i>Add Class</a></li>
                                        <li><a href="add_GroupClass.php"><i class="metismenu-icon"></i>Add Group Class</a></li>
                                        <li><a href="add_clinical_class.php"><i class="metismenu-icon"></i>Add Clinical Timetables</a></li>
                                        <li><a href="reschedule_class.php"><i class="metismenu-icon"></i>Reschedule Class</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="metismenu-icon pe-7s-date"></i>Timetable Management
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li><a href="publish_tentative.php"><i class="metismenu-icon"></i> Publish Tentative </a></li>
                                        <li><a href="confirm_module.php"><i class="metismenu-icon"></i>Confirm Timetable</a></li>


                                     <?php
                                     database_conectivity();
                                    $sql_filter = "SELECT CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  
                                    FROM `batchmodule` 
                                    JOIN module m ON m.m_code = batchmodule.m_code
                                     WHERE `ttprogress` IN ( 1, 2, 3) 
                                     AND
                                    ( cordi IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC; ";
                                    //echo $sql_filter;
                                     $val = ''; $val2 = '';
                                     $result = mysqli_query($conn, $sql_filter );
                                     while($row = mysqli_fetch_array($result)) {
                                     $val = $row["val"];
                                     $val2 = $row["val2"]; ?>
                                        <li><a href="timetable.php?id=<?php echo $val; ?>"><i class="metismenu-icon"></i><?php echo $val2; ?></a></li>
                                    <?php }     ?>

                                        <!--                                             <li><a href="import_batchClass.php?id=<?php /*echo $val; */?>"><i class="metismenu-icon"></i>Bulk Import </a></li>
                                   <li><a href="import_class.php?id=<?php echo $val; ?>"><i class="metismenu-icon"></i> Import </a></li>
                                        -->
                                                                     </ul>
                                </li>
                                <?php } ?>

                                <?php if($_SESSION["cat"]==5) { ?>
                                    <li><a href="registry_visitingStaff.php"><i class="metismenu-icon pe-7s-users"></i>Visiting Staff Registry </a></li>
                                    <li><a href="start_module.php"><i class="metismenu-icon pe-7s-angle-right"></i> Define Module</a></li>
                                    <li><a href="start_module.php"><i class="metismenu-icon pe-7s-angle-right"></i> Complete Module</a></li>
                                    <li><a href="add_class.php"><i class="metismenu-icon pe-7s-angle-right"></i>Add Class</a></li>
                                    <li><a href="add_GroupClass.php"><i class="metismenu-icon pe-7s-angle-right"></i>Add Group Class</a></li>
                                    <li><a href="reschedule_class.php"><i class="metismenu-icon pe-7s-angle-right"></i>Reschedule Class</a></li>
                                    <li><a href="history-module-calendar.php"><i class="metismenu-icon pe-7s-angle-right"></i>Previous Batch</a></li>
                                    <?php
                                    database_conectivity();
                                    $sql_filter = "SELECT CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  
                                    FROM `batchmodule` 
                                    JOIN module m ON m.m_code = batchmodule.m_code
                                     WHERE `ttprogress` IN ( 1, 2, 3) 
                                     AND
                                    ( cordi IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
                                     OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC; ";
                                    //echo $sql_filter;
                                    $val = ''; $val2 = '';
                                    $result = mysqli_query($conn, $sql_filter );
                                    while($row = mysqli_fetch_array($result)) {
                                        $val = $row["val"];
                                        $val2 = $row["val2"]; ?>
                                        <li><a href="timetable.php?id=<?php echo $val; ?>"><i class="metismenu-icon pe-7s-angle-right"></i><?php echo $val2; ?></a></li>
                                    <?php }     ?>

                                <?php } ?>
                                <?php if($_SESSION["cat"] == 4 || $_SESSION["cat"] == 5 ) { ?>
                                    <li>
                                        <a href="#"><i class="metismenu-icon pe-7s-note2"></i>Laboratory Reservations
                                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                        </a>
                                        <ul>
                                            <li><a href="reservation_schedule.php"><i class="metismenu-icon"></i>Laboratory Schedules</a></li>
                                            <li><a href="reserve_labs.php"><i class="metismenu-icon"></i>Reserve Laboratory </a></li>
                                            <li><a href="reservation_details.php"><i class="metismenu-icon"></i>Reservation Details</a></li>
                                     <!--     <li><a href="reservation_confirm.php"><i class="metismenu-icon"></i>Confirm Reservations</a></li>-->
                                         </ul>
                                    </li>
                                <?php } ?>
                                <?php if($_SESSION["cat"] <= 3  ) { ?>

                                <li>
                                    <a href="#"><i class="metismenu-icon pe-7s-note2"></i>Reports
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                       <li><a href="report_mod_lec_summary.php"><i class="metismenu-icon"></i>Module Lectures Summary</a></li>
                                       <li><a href="report_mod_reps_summary.php"><i class="metismenu-icon"></i>Module Reps Summary</a></li>

                                    </ul>
                                </li>
                                <?php } ?>
                            </ul>

                        </div>
                    </div>
                </div> 