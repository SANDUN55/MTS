<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
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
  

                    
<?php 
     $batchhMod = array(); $getVal='';
        if($_GET['id']) {
            $getVal = $_GET['id'];
            $batchhMod = explode('-', $getVal);
        }
?>
                     <div class="container" style = "background-color:white;">
                         <div id="calendar"></div>
                     <div>
    <script src="assets/scripts/calander/timetableDisplay-calender.js" data-params="<?php echo $getVal; ?>"></script>
<?php  } ?>