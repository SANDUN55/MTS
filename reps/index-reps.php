<?php
//error_reporting(E_ALL);
session_start();
if(!empty($_SESSION["stMtsFom"]) && $_SESSION["stLoggedIn"] === 1)
{
    echo "<script> alert('Please logout from the previous session');";
    header('Location:index-reps.php');
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
    <link rel="stylesheet" href="../app/assets/fullcalendar3.4.0.css" />
    <script src="../app/assets/jquery3.2.1.min.js"></script>
    <script src="../app/assets/jquery1.12.1-ui.min.js"></script>
    <script src="../app/assets/moment2.18.1.min.js"></script>
    <script src="../app/assets/fullcalendar3.4.0.min.js"></script>
    <script src="../app/assets/bootstrap3.4.1.min.js"></script>

<link href="../web/pages/main.css" rel="stylesheet">
    <script src="script.js"></script>
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
                                       <a href="index-reps.php">  <i class="fa text-white fa-home"></i></a>
                                    </button>
                                    <button type="button" class="btn-shadow btn btn-danger">
                                        <a href="logout.php"> <i class="fa text-white fa-sign-out-alt"></i></a>
                                    </button>
                                    <?php $userID = $_SESSION["stID"];//print_r($_SESSION);  ?>
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
                         <li class="app-sidebar__heading"></li> <i class="fa-sharp fa-solid fa-square-up-right"></i>
                            <li> <a href="index-reps.php"> <i class="metismenu-icon pe-7s-home"></i> Home </a></li>
                            <li> <a href="logout.php"> <i class="metismenu-icon pe-7s-close-circle"></i> Logout </a></li>
                         </ul>
                     </div>
                 </div>
             </div>
             <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
                                    </div>
                                    <div><h5><?php echo $_SESSION["stMtsFom"]; ?></h5>
                                       <!-- <div class="page-title-subheading"><h5>The dashboard will loads the classes details to add comments.</h5>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <?php
                                    include 'database.php';
                                    $bno = $_SESSION["stbMtsFom"];
                                   // echo "Select m_code, rep1,rep2,rep3,rep4  from batchmodule WHERE (rep1 = $userID OR rep2 = $userID OR rep3 = $userID  OR rep4 = $userID) AND b_no = $bno  AND DATE_ADD(en_dt, INTERVAL 7 DAY) < now()";
                                    $mysql_query = mysqli_query($conn, "Select m_code, rep1,rep2,rep3,rep4  from batchmodule WHERE (rep1 = $userID OR rep2 = $userID OR rep3 = $userID  OR rep4 = $userID) AND b_no = $bno");

                                    $str = array(); $mcodeval = array(); $i=0;
                                    while($mysql_rows = mysqli_fetch_array($mysql_query)){
                                        $mcodeval[$i] = $mysql_rows['m_code'];
                                        $str[$i][1] = $mysql_rows['rep1'];
                                        $str[$i][2] = $mysql_rows['rep2'];
                                        $str[$i][3] = $mysql_rows['rep3'];
                                        $str[$i][4] = $mysql_rows['rep4'];
                                        ++$i;
                                    }
                                   // print_r($str);echo "--";
                                   // print_r($mcodeval);echo "--";
                                    $len = count($mcodeval);//echo  $len;echo "--";
                                    for($x=0; $x<$len ; $x++){
                                        $key = array_search($userID, $str[$x]);
                                        echo $key;
                                        $feildvall[$x] = 'rep' . $key . '_comment';
                                    }
                                    //print_r($feildvall) ;
                                    ?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="container">
                                <div class="table-wrapper" >
                                    <div class="table-title">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h5 style="color: #06357a;"><b>* click on a lecture to add comment</b></h5>
                                                <select></select>
                                            </div>
                                        </div>
                                    </div>
                                          <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th  align="center">DATE/TIME</th>
                                                        <th  align="center">MODULE</th>
                                                        <th  align="center">LECTURE</th>
                                                        <th  align="center">COMMENT STATUS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php
//get the batch
//get the class details passed sessions
//print_r($mcodeval);
for($a=0; $a<$len ; $a++) {
    $feildval = $feildvall[$a];
    //echo $feildval;
    $repno = substr($feildval,3,1);
   // echo $repno;
    $mcode = $mcodeval[$a];
    //echo $mcode;
        $sql = "SELECT topic_id, class_id, a_name, class_topic, DATE(class_start) as dt , TIME (class_start) as sttm, TIME (class_end) as entm, 
        CONCAT (t_nm, \" \", firstname, \" \", surname) nm, div_nm, $feildval
        FROM classschedules 
        LEFT JOIN classtopics ON class_topic_id = topic_id 
        JOIN activity ON activity  = a_id
        JOIN staff ON staff = st_id
	WHERE b_no = $bno AND m_code = '$mcode' AND class_end < now() AND class_status = 1 AND $feildval=0  ORDER BY class_start DESC;  ";
        //ND (st_comment_rep <> 1 AND st_comment2_rep <>1 )
    // echo $sql; $mcode
    error_log($sql);
            /*AND  rep$loggedrep = 0 ";*/
//echo $sql;
            $result = mysqli_query($conn, $sql);
            $i = 1;
            while ($row = mysqli_fetch_array($result)) {
                $sttm = strtotime($row["sttm"]);
                $entm = strtotime($row["entm"]);
                $data = $row["topic_id"] . '-' . $row["class_id"] . '-' .$repno;
                $dataval = $row["a_name"] . ' : ' . $row["class_topic"] . ' by ' . $row["nm"] . ' (' . $row["div_nm"] . ')';
                $commentSt = $row[$feildval];
                ?>
                <tr id="<?php echo $i; ?>" class="edit" data-toggle="modal" data-id="<?php echo $data; ?>"
                    data-val="<?php echo $dataval; ?>"
                    data-target="<?php if ($commentSt < 1) echo "#commentModal"; else echo "#commentModalDone"; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row["dt"] . '<br>' . date('h:i', $sttm) . ' - ' . date('h:i a', $entm); ?></td>
                    <td><?php echo $mcode; ?></td>
                    <td><?php echo $row["a_name"] . ' - ' . $row["class_topic"] . '<br>' . $row["nm"] . ' (' . $row["div_nm"] . ')'; ?></td>
                    <td>
                        <?php if ($commentSt >= 1) echo "<i class='fa fa-check icon-gradient bg-malibu-beach'></i>"; ?>

                    </td>
                </tr>
                <?php
                $i++;
            }
}
?>
                                                </tbody>
                                            </table>
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
                                    <p>© 2023, HDSU, Faculty of Medicine. University of Kelaniya. All Rights Reserved</p>
                                </div>
                            </div>
                        </div>
                    </div>
             </div>
        </div>
    </div>
<script type="text/javascript" src="../app/assets/main.js"></script>
</body>
<div id="commentModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="repsComments" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Add Comment on Lecture</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <input type="hidden" id="id_rep" name="rid" class="form-control" value="<?php echo $userID; ?>">
                    <input type="hidden" id="id_key" name="key" class="form-control" value="<?php echo $key; ?>">

                  <h4>  <div id="id_v"> </div>   </h4>
                </br>
                </hr>
                    <p style="text-align: center">
                            <button type="button" class="btn btn-success" id="btnClick" value="1">Conducted</button> <br><br>
                            <button type="button" class="btn btn-danger" id="btnClick" value="2">Not-Conducted</button> <br><br>
                            <button type="button" class="btn btn-primary" id="btnClick" value="3">Postponed</button>
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-secondary" data-dismiss="modal" value="CLOSE">

                </div>
            </form>
        </div>
    </div>
</div>
<div id="commentModalDone" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="repsComments" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Comment on Lecture</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <div id="id_v"> </div>
                    </br>
                    </hr>
                </div>
                <h5> <p style="color: #AF2018">&nbsp; You have already comment on this Lecture</p></h5>
                <div class="modal-footer">
                    <input type="button" class="btn btn-secondary" data-dismiss="modal" value="CLOSE">

                </div>
            </form>
        </div>
    </div>
</div>
</html>
<?php } ?>
