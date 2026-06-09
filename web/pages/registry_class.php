<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
    {
        header('Location:../login.php');
        die();
    }else {
?>
    <?php include 'headtag.php'; ?>
    <script src="assets/scripts/batch.js"></script>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>   
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
<?php include 'assets/scripts/backend/database.php'; ?>
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Registries</a></li>
            <li class="active breadcrumb-item" aria-current="page">Class</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Class Registry</b></h2>
					</div>
					<div class="col-sm-4" align="right" style="padding-bottom:10px">
						<a href="#addBatchModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New Batch</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
						<th  align="center">BATCH NO</th>
                        <th  align="center">MODULE NAME</th>
						<th>STATUS</th>
						<th>ACTION</th>
                    </tr>
                </thead>
				<tbody>
				
				<?php
                $result = mysqli_query($conn, "SELECT CONCAT( b_no, '-' , m.m_code) as val, b_no, CONCAT( m.m_name, ' ', m.m_phase) as val2  
FROM `batchmodule` JOIN module m ON m.m_code = batchmodule.m_code
 WHERE cordi IN (SELECT st_id FROM staff WHERE username='$user')
 OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user') ; ");
                while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["val"]; ?>">
					<td><?php echo $i; ?></td>
					<td align="center"><?php echo $row["b_no"]; ?></td>
					<td align="center"><?php echo $row["val2"]; ?></td>
                    <td align="center">
                    </td>
					<td align="center">
                        <a href="#addclasshModal" class="btn btn-success" data-toggle="modal">
                            <i class="pe-7s-plus update" data-toggle="tooltip"
                               data-id="<?php echo $row["val"]; ?>"
                               title="Add Class"></i>
                        </a>
						<a href="#deleteBatchModal" class="delete" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-trash  icon-gradient bg-love-kiss" data-toggle="tooltip" title="Delete"></i></a>
                        <a href="#disableBatchModal" class="disable" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-close-circle  icon-gradient bg-happy-itmeo" data-toggle="tooltip" title="Disable"></i></a>
                        <a href="#upgradeBatchModal" class="upgrade" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-up-arrow icon-gradient bg-plum-plate" data-toggle="tooltip" title="Upgrade"></i></a>

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
<script type="text/javascript" src="assets/scripts/main.js"></script></body>
	<!-- Add Modal HTML -->
	<div id="addclasshModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form" class="needs-validation">
					<div class="modal-header">						
						<h4 class="modal-title">Add Batch</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Batch Number</label>
<?php
$sql_lastBatchNo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(b_no) as bno  FROM batch"));
$lastBatchNo=$sql_lastBatchNo['bno'];
?>
							<input type="text" id="bno" name="bno" class="form-control" value="<?php echo $lastBatchNo+1; ?>" required>
						</div>
						<div class="form-group">
							<label>Academic Year</label>
<?php
$sql_lastBatchYr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(b_yr) as byr  FROM batch"));
$lastBatchYr=$sql_lastBatchYr['byr'];
$years = explode("/", $lastBatchYr);
$firstYear=$years[1];
$lastYear=$years[1]+1;
$newAcademicYear=$firstYear.'/'.$lastYear;
?>							
							<input type="text" id="byr" name="byr" class="form-control" value="<?php echo $newAcademicYear; ?>" required>
                            <div class="valid-tooltip">
                                Looks good!
                            </div>
						</div>
						<div class="form-group">
							<label>Code (Two charcters Only)</label>
							<input type="text" id="bcode" name="bcode" class="form-control" pattern="[A-Za-z]{2}" required
                                   oninvalid="this.setCustomValidity('Enter Batch Code here')"
                                   oninput="this.setCustomValidity('')"
                            >
						</div>			
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" class="btn btn-success" id="btn-add">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script>
    $("#addBatchModal").on("hidden.bs.modal",function(){
        $('#user_form')[0].reset();
    });
</script>
<!-- Edit Modal HTML -->
<div id="editBatchModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>
                    <div class="form-group">
                        <label>Batch No</label>
                        <input type="text" id="bno_u" name="bno" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Academic Year</label>
                        <input type="text" id="byr_u" name="byr" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Code (Two charcters Only)</label>
                        <input type="text" id="code_u" name="bcode" class="form-control" pattern="[A-Za-z]{2}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="2" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-info" id="update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteBatchModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Delete Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p>Are you sure you want to <span class="text-danger">DELETE</span>  this Record?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Disable Modal HTML -->
<div id="disableBatchModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Disable Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p>Are you sure you want to <span class="text-danger">DISABLE</span> this Record?</p>
                    <p class="text-primary"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="disable">Disable</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Upgrade Modal HTML -->
<div id="upgradeBatchModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Upgrade Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p>Are you sure you want to <span class="text-alternate">UPGRADE</span> this Batch?</p>
                    <p class="text-alternate"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-alternate" id="upgrade">Upgrade</button>
                </div>
            </form>
        </div>
    </div>
</div>
</html>
<?php } ?>