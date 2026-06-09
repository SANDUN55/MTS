<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>
<?php include 'header-top.php'; ?>
    <title>Module Timetable Calender</title>
 <!--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="assets/scripts/calander/3.4.1bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
-->
    <link rel="stylesheet" href="assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <script src="assets/scripts/calander/bootstrap3.4.1.min.js"></script>
    <script src=" assets/scripts/timetableMy.js"></script>

<div class="app-main">
<?php include 'navbar-left.php'; ?>
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
                       
                     </div>
                     <div class="container">
                         <div id="calendar"></div>
                     <div>
                 </div>
             </div>
         </div>
    </div>
 </div>
<?php include 'footer.php'; ?>
<?php if($_SESSION["cat"] == 6){ ?>

    <script src="assets/scripts/calander/timetableMy-calender.js" data-params="<?php echo $getVal; ?>"></script>
<?php } else { ?>
    <script src="assets/scripts/calander/timetableDisplay-calender.js" data-params="<?php echo $getVal; ?>"></script>
<?php } ?>
</div>
<?php  } ?>