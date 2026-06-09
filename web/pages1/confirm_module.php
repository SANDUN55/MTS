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
            <li class="breadcrumb-item"><a href="registry_home.php">Confirm Timetable </a></li>
            <li class="active breadcrumb-item" aria-current="page">View Moudule List</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Module List</b></h2>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th  align="center">BATCH</th>
                        <th  align="center">MODULE NAME</th>
                        <th  align="center">SUMMARY</th>
                        <th  align="center">CONFIRM</th>
                        <th></th>
                    </tr>
                </thead>
				<tbody>
<?php
$sql = "SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm
FROM `batchmodule` 
JOIN module m ON m.m_code = batchmodule.m_code
 WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL) AND (conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL) 
 AND st_dt <> '0000-00-00 00:00:00' AND 	en_dt <> '0000-00-00 00:00:00' AND ttprogress = 0 AND 
( cordi IN (SELECT st_id FROM staff WHERE username='$user') OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC; ";
//echo $sql;
database_conectivity();
$result = mysqli_query($conn,$sql);
$i=1;
while($row = mysqli_fetch_array($result)) {
?>
				<tr id="<?php echo $row["b_no"]; ?>">
					<td align="center"><?php echo $row["b_no"]; ?></td>
					<td><?php echo $row["modnm"]; ?></td>
                    <td> <a href="#displaySummary" class="edit" data-toggle="modal">
                            <i class="pe-7s-note2 icon-gradient bg-sunny-morning summary" data-toggle="tooltip"
                               data-id="<?php echo $row["b_no"].','.$row["modnm"]; ?>"
                               data-id2="<?php echo $row["m_code"]; ?>"
                               title="View Summary"></i>
                        </a>
                    </td>
                    <td> <a href="#confirmBatchModule" class="" data-toggle="modal">
                            <i class="pe-7s-lock icon-gradient bg-sunny-morning confirm" data-toggle="tooltip"
                               data-id="<?php echo $row["b_no"]; ?>"
                               data-id2="<?php echo $row["m_code"]; ?>"
                               data-id3="<?php echo $row["modnm"]; ?>"
                               title="Confirm Module"></i>
                        </a>
                    </td>
				</tr>
				<?php
				$i++;
				}
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
<!-- Edit Modal HTML -->
<div id="displaySummary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateDT_form">
                <div class="modal-header">
                    <h4 class="modal-title">SUMMARY <div id="batch"></div> <div id="modn"></div> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>
                    <input type="hidden" id="id_m" name="mcode" class="form-control" required>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th  align="center">ACTIVITY TYPE</th>
                            <th  align="center">COUNT</th>
                        </tr>
                        </thead>
                        <tbody id="theBody">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#displaySummary").on("show.bs.modal",function(){
        var str1 = document.getElementById("id_u").value;
        var str2 = document.getElementById("id_m").value;
        var res = str1.split(",");
        var bno = 'Batch ' + res[0];
        var modnm = res[1];
        //alert(res[1]);
        $('#batch').html(bno);
        $('#modn').html(modnm);
        var val = res[0] + ',' + str2;
        $.ajax({
            type: "POST",
            url: "assets/scripts/backend/get_val.php",
            data:'fid='+ 3 + '&fval=' + val,
            success: function(data){
                //alert(data);
                document.getElementById('theBody').innerHTML = data;
                //$("#summary").html(data);
            }
        });
    });
</script>

<div id="confirmBatchModule" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="confirm_form">

                <div class="modal-header">
                    <h4 class="modal-title">Confirm Module</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="bno" name="bno" class="form-control">
                    <input type="hidden" id="mco" name="mco" class="form-control" required>
                    <input type="hidden" id="mnm" name="mnm" class="form-control">
                    <p>Are you sure you want to CONFIRM  this Record?</p>
                    <div id="binfo"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="7" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="confimMod">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
<?php } ?>