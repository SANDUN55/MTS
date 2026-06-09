<style>
    .app-sidebar sidebar-shadow{
        background-color: #499127; !important;
    }
</style>

             <div class="app-sidebar sidebar-shadow sidebar-text-light">
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
                             <li class="app-sidebar__heading">
                                 <a href="index.php"> <i class="metismenu-icon pe-7s-home"></i> Dashboard </a>
                             </li>
                             <li class="app-sidebar__heading">
                                 <a href="logout.php"> <i class="metismenu-icon pe-7s-power"></i> Logout </a>
                             </li>
                             <li> <a href="myClass.php"> <i class="metismenu-icon pe-7s-date"></i> View my Lectures </a></li>
                             <li> <a href="req_send.php"> <i class="metismenu-icon pe-7s-ticket"></i> Request change </a></li>
                             <li> <a href="req_check.php"> <i class="metismenu-icon pe-7s-ribbon"></i> Request Status </a></li>
                             <li> <a href="myClassSummary.php"> <i class="metismenu-icon pe-7s-ribbon"></i> My Class Reports </a></li>
                             <li> <a href="totalLectureHours.php"> <i class="metismenu-icon pe-7s-ribbon"></i> Total Lecture Hours </a></li>
                             <li> <a href="myMeetingSummary.php"> <i class="metismenu-icon pe-7s-ribbon"></i> My Meeting Reports </a></li>
                                <hr> <hr>
                             <li class="sidebar-text-light"> Tentative Timetables </li>
                            <?php
                            include 'database.php';
                            $sql = "SELECT CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, 
                            CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  , ttprogress
                            FROM `batchmodule` 
                            JOIN module m ON m.m_code = batchmodule.m_code
                            WHERE ttprogress = 2 AND CONCAT(b_no, m.m_code) 
                            IN (SELECT concat( b_no, m_code ) FROM classtopics WHERE dep_code != staff AND staff = $userID ); ";
                            //echo $sql;WHERE NOW() BETWEEN st_dt AND en_dt AND ttprogress = 2 AND
                            $result = mysqli_query($conn, $sql) or die(mysqli_error());
                            while($row = mysqli_fetch_array($result)) {
                                $val = $row["val"];
                                $val2 = $row["val2"];
                                $ttp = $row["ttprogress"];

                                ?>
                                <li><a href="timetableMy.php?id=<?php echo $val; ?>"><i class="metismenu-icon"></i><?php echo $val2; ?></a></li>
                            <?php }     ?>
                             <hr> <hr>
                             <li> Confirmed Timetables </li>
                             <?php
                             include 'database.php';
                             $sql = "SELECT CONCAT( b_no, '-' , m.m_code, ' - ', m.m_name, ' ', m.m_phase ) as val, 
                            CONCAT(b_no, ' - ', m.m_name, ' ', m.m_phase) as val2  , ttprogress
                            FROM `batchmodule` 
                            JOIN module m ON m.m_code = batchmodule.m_code
                            WHERE ttprogress = 3 AND CONCAT(b_no, m.m_code) 
                            IN (SELECT concat( b_no, m_code ) FROM classtopics WHERE dep_code != staff AND staff = $userID ); ";
                             //echo $sql;
                             $result = mysqli_query($conn, $sql) or die(mysqli_error());
                             while($row = mysqli_fetch_array($result)) {
                                 $val = $row["val"];
                                 $val2 = $row["val2"];
                                 $ttp = $row["ttprogress"];

                                 ?>
                                 <li><a href="timetableMy.php?id=<?php echo $val; ?>"><i class="metismenu-icon"></i><?php echo $val2; ?></a></li>
                             <?php }     ?>
                             <hr> <hr>
                         </ul>
                     </div>
                 </div>
             </div>