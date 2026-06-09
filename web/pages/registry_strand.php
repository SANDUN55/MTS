<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
    <?php include 'headtag.php'; ?>
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
            <li class="breadcrumb-item"><a href="registry_home.php">Registries</a></li>
            <li class="active breadcrumb-item" aria-current="page">Batch</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-settings"></i> <b>Strand Registry</b></h2>
					</div>
					<div class="col-sm-4" align="right" style="padding-bottom:10px" >
						<a href="#addStrandModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New Strand</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
						<th>STRAND NAME</th>
                        <th>ABBREVIATION</th>
                        <th>STATUS</th>
						<th>ACTION</th>
                    </tr>
                </thead>
				<tbody>
				<?php
                database_conectivity();
				$result = mysqli_query($conn,"SELECT * FROM strand ORDER BY sn_id ASC;");
					$i=1;
					while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["sn_id"]; ?>">
					<td><?php echo $i; ?></td>
					<td><?php echo $row["sn_nm"]; ?></td>
					<td><?php echo $row["sn_abr"]; ?></td>
                    <td align="center">
 <?php
 if($row["sn_st"]==0) echo "Disable";
 if($row["sn_st"]==1) echo "Active";
 ?>
                    </td>
					<td align="center">
  <?php  if($row["sn_st"]==1) { ?>
                        <a href="#editStrandModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen update" data-toggle="tooltip"
                               data-id="<?php echo $row["sn_id"]; ?>"
                               data-bno="<?php echo $row["sn_nm"]; ?>"
                               data-byr="<?php echo $row["sn_abr"]; ?>"
                               title="Edit"></i>
                        </a>
						<a href="#deleteStrandModal" class="delete" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-trash icon-gradient bg-love-kiss" data-toggle="tooltip" title="Delete"></i></a>
                        <a href="#disableStrandModal" class="disable" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-door-lock icon-gradient bg-happy-itmeo" data-toggle="tooltip" title="Disable"></i></a>
  <?php } ?>
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
                <?php include 'footer.php';
                $logAction="Load Strand Registry";
                writeLog($logAction);
                ?>

            </div>
        </div>
    </div>
<script type="text/javascript" src="assets/scripts/main.js"></script></body>
<script>
    $("#addBatchModal").on("hidden.bs.modal",function(){
        $('#user_form')[0].reset();
    });
</script>
	<!-- Add Modal HTML -->
	<div id="addStrandModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Add Strand</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					

					</div>
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-success" id="btn-add">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!-- Edit Modal HTML -->
<div id="editStrandModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Strand</h4>
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
<div id="deleteStrandModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Delete Strand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p>Are you sure you want to delete this Record?</p>
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
<div id="disableStrandModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Disable Strand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p>Are you sure you want to disable this Record?</p>
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
</html>
<?php } ?>