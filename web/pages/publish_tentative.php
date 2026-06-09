<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
    {
        header('Location:../login.php');
        die();
    }else {
?>
    <?php include 'headtag.php'; ?>
    <script src="assets/scripts/int_module.js"></script>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>

<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
<?php //include 'assets/scripts/backend/database.php'; ?>
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Timetable Management </a></li>
            <li class="active breadcrumb-item" aria-current="page">Publish Tentative Timetable</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-note2"></i> <b>Publish Tentative Timetable</b></h2>
					</div>
                </div>
            </div>
<?php
$sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm, st_dt, en_dt FROM batchmodule 
JOIN module m ON m.m_code = batchmodule.m_code
 WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL) AND  (conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL) AND ttprogress <= 1
 AND (cordi IN (SELECT st_id FROM staff WHERE username='$user')
 OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user') )
 ORDER BY b_no DESC;";
//echo $sql;
database_conectivity();
$result = mysqli_query($conn,$sql);
?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
                        <th  align="center">BATCH - MODULE </th>
                        <th  align="center">START DATE - END DATE (YY-MM-DD)</th>
                      <!--  <th  align="center"> DETAILS</th>-->
                        <th  align="right">ACTION</th>
                    </tr>
                </thead>
				<tbody>
<?php
$i=1; $bno = ''; $mod = '';
$rowcount = mysqli_num_rows($result);
if($rowcount == 0) echo "<p class=\"text-danger\"><b>NO RECORDS TO DISPLAY !</b><p>";
while($row = mysqli_fetch_array($result)) {
    $bno = $row["b_no"];
    $mod = $row["m_code"];
?>
				<tr id="<?php echo $row["b_no"]; ?>">
					<td style = "vertical-align : top !important;"><?php echo $i; ?></td>
					<td style = "vertical-align : top !important;"><?php echo  $bno . ' - ' . $row["modnm"] ; ?></td>
                    <td style = "vertical-align : top !important;"><?php echo  $row["st_dt"] . ' To ' . $row["en_dt"]; ?></td>
                   <!-- <td>-->
<?php
/*$sql = "SELECT a_id, a_name, activity, count(*) AS ct FROM classschedules
              JOIN classtopics ON class_topic_id = topic_id 
              JOIN activity ON activity = a_id 
              WHERE b_no = $bno AND m_code = '$mod' AND class_status = 1 
              GROUP BY activity ORDER BY a_name ";*/
/*$sql = "SELECT div_nm,  a_name, activity, count(*) AS ct FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
JOIN activity ON activity = a_id
JOIN divisions ON dep_code = div_id
WHERE b_no = $bno AND m_code = '$mod' AND class_status = 1 GROUP BY  activity, dep_code 
ORDER BY a_name, div_nm";

echo $sql;
$result = mysqli_query($conn,$sql);
$str = "";
while($row = mysqli_fetch_array($result))
{
    $str .= $row["a_name"]. ' : ' . $row["div_nm"] . '     -   ' . $row["ct"] . "<br>";
}
echo $str;*/
//$DateChk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT hid  FROM holidays  WHERE '$classDt' between startDt AND enddt"));
?>
                   <!-- </td>-->
               <td> <a href="#pubTentativeTimetable" class="end" data-toggle="modal">
                            <button class="mb-2 btn btn-info"><i class="pe-7s-paper-plane  pubTentative" data-toggle="tooltip"
                                                                  data-id3="<?php echo $bno.','.$mod; ?>"> Publish Tentative Timetable</i></button>
                        </a></td>
                    </td>
				</tr>
				<?php
				$i++;
				}
                    $logAction="Load page publish tentative timtable";
                    writeLog($logAction);
				?>
				</tbody>
			</table>
        </div>

				</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>
</body>
<!-- End Modal HTML -->
<div id="pubTentativeTimetable" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title">Publish Tentative Timetable</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_dtn" name="id" class="form-control">
                    <p>Are you sure you want to <span class="text-danger">PUBLISH</span> TENTATIVE Timetable ?</p>
                    <p class="text-primary"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-secondary" data-dismiss="modal" value="CLOSE">
                    <button type="button" class="btn btn-danger" id="publishTentv">PUBLISH TENTATIVE TIMETABLE</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
<?php } ?>