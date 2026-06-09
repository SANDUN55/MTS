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
<?php include 'assets/scripts/backend/database.php'; ?>
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Registries</a></li>
            <li class="active breadcrumb-item" aria-current="page">Batch</li>
        </ol>
    </nav>
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Phase Registry</b></h2>
					</div>
					<div class="col-sm-4" align="right" style="padding-bottom:10px">
						<a href="#addPhaseModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New Phase</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
						<th  align="center">PHASE NAME</th>
                        <th  align="center">STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
				<tbody>
				
				<?php
                database_conectivity();
				$result = mysqli_query($conn,"SELECT * FROM phase ORDER BY p_id ASC ;");
					$i=1;
					while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["p_id"]; ?>">
					<td><?php echo $i; ?></td>
					<td align="center"><?php echo $row["p_nm"]; ?></td>
					<td align="center">
 <?php
 if($row["p_st"]==0) echo "Disable";
 if($row["p_st"]==1) echo "Active";
 ?>
                    </td>
					<td align="center">
  <?php  if($row["p_st"]==1) { ?>
                        <a href="#editPhaseModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning update update" data-toggle="tooltip"
                               data-id="<?php echo $row["p_nm"]; ?>"
                               title="Edit"></i>
                        </a>
						<a href="#deletePhaseModal" class="delete" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-trash icon-gradient bg-love-kiss" data-toggle="tooltip" title="Delete"></i></a>
                        <a href="#disablePhaseModal" class="disable" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-door-lock  icon-gradient bg-happy-itmeo" data-toggle="tooltip" title="Disable"></i></a>
                        <a href="#upgradePhaseModal" class="upgrade" data-id="<?php echo $row["b_no"]; ?>" data-toggle="modal"><i class="pe-7s-up-arrow icon-gradient bg-plum-plate" data-toggle="tooltip" title="Upgrade"></i></a>
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
                $logAction="Load Phase Registry";
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
	<div id="addPhaseModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Add Phase</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
<div id="editPhaseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Phase</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
<div id="deletePhaseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Delete Phase</h4>
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
<div id="disablePhaseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Disable Phase</h4>
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