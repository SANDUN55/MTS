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
            <li class="breadcrumb-item"><a href="registry_home.php">Module Management </a></li>
            <li class="active breadcrumb-item" aria-current="page">Set Module Dates</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Module Dates</b></h2>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
                        <th  align="center">BATCH</th>
                        <th  align="center">MODULE</th>
                        <th  align="center">START DATE (YY-MM-DD)</th>
                        <th></th>
                        <th  align="center">END DATE (YY-MM-DD)</th>
                        <th></th>
                        <th  align="right">END MODULE</th>
                    </tr>
                </thead>
				<tbody>
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
$i=1;
while($row = mysqli_fetch_array($result)) {
?>
				<tr id="<?php echo $row["b_no"]; ?>">
					<td><?php echo $i; ?></td>
					<td align="center"><?php echo $row["b_no"]; ?></td>
					<td><?php echo $row["modnm"]; ?></td>
                    <td><?php echo $row["st_dt"]; ?></td>
                    <td  align="left"> <a href="#editDateModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning updateDate" data-toggle="tooltip"
                               data-id1="<?php echo $row["b_no"].','.$row["m_code"].',C1'; ?>"
                               data-bn="<?php echo $row["b_no"]; ?>"
                               data-mc="<?php echo $row["modnm"]; ?>"
                               data-md="<?php  echo  $row["st_dt"]; ?>"
                               title="Edit"></i>
                        </a></td>
                    <td><?php echo $row["en_dt"]; ?></td>
                    <td align="left"> <a href="#editDateModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning updateDate" data-toggle="tooltip"
                               data-id1="<?php echo $row["b_no"].','.$row["m_code"].',C2'; ?>"
                               data-bn="<?php echo $row["b_no"]; ?>"
                               data-mc="<?php echo $row["modnm"]; ?>"
                               data-md="<?php  echo  $row["en_dt"]; ?>"
                               title="Edit"></i>
                        </a></td>
                    </td>
                    <td align="right"> <a href="#endModuleModal" class="end" data-toggle="modal">
                            <i class="pe-7s-close-circle icon-gradient bg-danger endModule" data-toggle="tooltip"
                               data-id2="<?php echo $row["b_no"].','.$row["m_code"]; ?>"
                               title="End Module"></i>
                        </a></td>
                    </td>
				</tr>
				<?php
				$i++;
				}
                    $logAction="Load Define Module";
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
<!-- Edit Modal HTML -->
<div id="editDateModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateDT_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Start / End Date</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>
                    <div class="form-group">
                        <label>Batch</label>
                        <input type="text" id="bno_u" name="bno" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label>Module Name</label>
                        <input type="text" id="bmd_u" name="modname" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label>Date (MM/DD/YY)</label>
                        <input type="date" id="date1" name="classDate"   class="form-control" required >
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="5" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-info" id="updateDt">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal HTML -->
<div id="endModuleModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">End Module</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p>Are you sure you want to <span class="text-danger">END</span> this Module?</p>
                    <p class="text-primary"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="endModule">END</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
<?php } ?>