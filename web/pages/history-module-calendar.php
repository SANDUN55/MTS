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
    <link rel="stylesheet" href="assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <link rel="stylesheet" href="assets/scripts/calander/bootstrap3.4.1.min.css">
    <script src="assets/scripts/calander/bootstrap3.4.1.min.js"></script>
    <script src="assets/scripts/timetable.js"></script>
    <style>
        .answer { display:none }
    </style>
    <?php  include 'assets/scripts/backend/select_val.php';?>
    <div class="app-main" style="height: 100%">
        <?php include 'navbar-left.php'; ?>
        <div class="app-main__outer" style="height: 100%">
            <div class="app-main__inner" style="height: 100%">
                <div class="row" style="height: 100%">

                         <!-- </div>-->
                    <div class="col-12" style="height: 100%">
                        <div class="main-card mb-3 card" style="height: 100%">
                            <div class="card-body" style="height: 100%">
                                    <label>Select Batch - Module </label>
                                        <?php   //loadBatchModuleMy();
                                        database_conectivity();
                                        /*$sql_filter = "SELECT CONCAT( b_no, '-' , m.m_code) as val
                                                         FROM `batchmodule` 
                                                        JOIN module m ON m.m_code = batchmodule.m_code
                                                        WHERE `ttprogress` = 4 ORDER BY b_no DESC; ";*/
                                        $sql_filter = "SELECT  MAX(b_no) As bno, m.m_code, m.m_name, st_dt, en_dt FROM `batchmodule` 
                                                            JOIN module m ON m.m_code = batchmodule.m_code WHERE `ttprogress` = 4  group by m_code ORDER BY b_no DESC;";
                                       // echo $sql_filter;
                                        $result = mysqli_query($conn, $sql_filter );

                                        ?>
                                <select name="selectBatchMo" id="selectBatchMo" class="form-control" >
                                    <option value="">select</option>
                                    <?php       while($row = mysqli_fetch_array($result)) {?>
                                        <option value="<?php echo $row["bno"]. '-' . $row["m_code"]; ?>"><?php echo $row["bno"]. ' - ' . $row["m_name"] . " - (" . $row["st_dt"] . " to ". $row["en_dt"]  . ")" ; ?></option>
                                    <?php }     ?>
                                </select>

                                <script>
                                    $('#selectBatchMo').on('change', function() {
                                            var val = this.value;
                                          // alert(val);
                                        var durl = "timetableDisplay.php?id=" + val;
                                        $("#displayTT #myIframe").attr('src', durl);
                                    });
                                        </script>
                               <br>
                            </div>

                            <div  id="displayTT" style="height: 100%">
                                <iframe id="myIframe" src="" height="500"  width="100%" scrolling="yes" frameborder="0" src = >

                                </iframe>
                            </div>
                        </div>
                    </div><div class="row">
                        <div class="col">
                            <?php include 'footer.php'; ?>
                        </div>
                    </div>
                </div>
            </div></div></div>
   <!-- <script src="assets/scripts/history-module-calender.js" data-params="<?php /*echo $getVal; */?>"></script>-->
    </html>
<?php  } ?>