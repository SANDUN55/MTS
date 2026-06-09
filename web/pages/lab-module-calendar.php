<?php //include 'headtag.php'; ?>
<link rel="icon" href="assets/images/favicon.png" sizes="32x32">
<link rel="icon" href="assets/images/favicon.png" sizes="192x192">
<link rel="apple-touch-icon" href="assets/images/favicon.png">
<meta name="msapplication-TileImage" content=    "assets/images/favicon.png">
<link href="main.css" rel="stylesheet">

<script type="text/javascript" src="assets/scripts/autologout-2.js"></script>
<link rel="stylesheet" href="assets/scripts/calander/fullcalendar3.4.0.css" />
<script src="assets/scripts/calander/jquery3.2.1.min.js"></script>
<script src="assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
<script src="assets/scripts/calander/moment2.18.1.min.js"></script>
<script src="assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
<!--<link rel="stylesheet" href="assets/scripts/calander/bootstrap3.4.1.min.css">
<script src="assets/scripts/calander/bootstrap3.4.1.min.js"></script>-->
<style>
    .answer { display:none }
</style>
<?php include 'header-top.php'; ?>
<body>

        <?php include 'header-top.php'; ?>
         <div class="app-main">
             <?php include 'navbar-left.php'; ?>
             <div class="app-main__outer">
                    <div class="app-main__inner">
                                        <div class="row">
                                            <div class="col-12" style="text-align: center" >
                                                <?php
                                                $batchhMod = array();
                                                $urlData = $_GET['id'] ;
                                                if($urlData) {
                                                    $batchhMod = explode('-', $urlData);
                                                }
                                                ?><h2 align="center"> Batch <?php  echo $batchhMod[0]. ' - ' . $batchhMod[2]. ' Module' ; ?>  </h2>
                                            </div>
                                            <div id='mcalendar'></div>
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

    <script type="text/javascript" src="assets/scripts/calander/lab-timetable-calender.js" data-params="<?php echo $urlData; ?>"></script>
</body>
</html>
