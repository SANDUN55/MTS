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

    <?php  include 'assets/scripts/backend/select_val.php';?>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>
 <?php //include 'assets/scripts/backend/database.php'; ?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
                       
         	<div class="main-card mb-3 card">
                <div class="card-body">

<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">User Management</a></li>
            <li class="active breadcrumb-item" aria-current="page">Assign Reps to Module</li>
        </ol>
    </nav>
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-users"></i> <b>Assign Reps to Module</b></h2>
					</div>
                    <div class="col-sm-4" align="right">
                        <a href="#editRepModModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add Rep to Module</span></a>
                    </div>
                </div>
            </div>
<?php
database_conectivity();
$sql = "SELECT bm.b_no, bm.m_code, CONCAT (m_name, ' ', m_phase) as mod_nm, rep1 , 
CONCAT (r1.st_no, '<hr>', r1.st_nm) as rep1_nm, rep2 , 
CONCAT (r2.st_no, '<hr>', r2.st_nm) as rep2_nm, rep3 , 
CONCAT (r3.st_no, '<hr>', r3.st_nm) as rep3_nm, rep4 , 
CONCAT (r4.st_no, '<hr>', r4.st_nm) as rep4_nm 
FROM batchmodule bm 
LEFT JOIN module m ON bm.m_code = m.m_code 
LEFT JOIN batchreps r1 ON rep1 = r1.rep_id 
LEFT JOIN batchreps r2 ON rep2 = r2.rep_id 
LEFT JOIN batchreps r3 ON rep3 = r3.rep_id 
LEFT JOIN batchreps r4 ON rep4 = r4.rep_id 
WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL)  AND ttprogress <= 3
ORDER BY bm.b_no DESC , m_phase, m_name;";
$result = mysqli_query($conn,$sql);
?>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th  align="center">BATCH NO</th>
                    <th  align="center">MODULE NAME</th>
                    <th>Rep 1</th>
                    <th> </th>
                    <th>Rep 2</th>
                    <th> </th>
                    <th>Rep 3</th>
                    <th> </th>
                    <th>Rep 4</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
<?php
$x=0; $editOn = 0;
while($row = mysqli_fetch_array($result)) {
    $val = $row["b_no"] . "," . $row["m_code"];

    ?>
				<tr id="<?php echo $row["b_no"]; ?>">
                    <td><?php echo $row["b_no"]; ?></td>
					<td><?php echo $row["mod_nm"]; ?></td>
                    <td align="center"><?php echo $row["rep1_nm"]; ?></td>
                    <td align="center"><?php if($row["rep1"] <> 0) {  ?>
                         <a href="#deleteRepModModal" class="delete" data-id="<?php echo $val.',R1'; ?>" data-toggle="modal">
                             <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                         </a>
                        <?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["rep2_nm"]; ?></td>
                    <td align="center"><?php if($row["rep2"] <> 0) {  ?>
                        <a href="#deleteRepModModal" class="delete" data-id="<?php echo $val.',R2'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                         </a>
                        <?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["rep3_nm"]; ?></td>
                    <td align="center"><?php if($row["rep3"] <> 0) {  ?>
                            <a href="#deleteRepModModal" class="delete" data-id="<?php echo $val.',R3'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                            </a>
                        <?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["rep4_nm"]; ?></td>
                    <td align="center"><?php if($row["rep4"] <> 0) {  ?>
                            <a href="#deleteRepModModal" class="delete" data-id="<?php echo $val.',R4'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                            </a>
                            <?php  } ?>
                    </td>

				</tr>
    <?php $x++; 	}    ?>
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
<script type="text/javascript" src="assets/scripts/main.js"></script>
        !-- Edit Modal HTML -->
<div id="editRepModModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Batch Representatives</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Batch</label>
                        <?php  loadBatch(); ?>
                    </div>
                    <script>
                        $('#selectBatch').on('change', function() {
                            var val = this.value;
                            $.ajax({
                                type: "POST",
                                url: "assets/scripts/backend/get_val.php",
                                data:'fid=' +  1  +  '&fval=' + val,
                                success: function(data){
                                    $("#mcode").html(data);
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "assets/scripts/backend/get_val.php",
                                data:'fid=' +  8  +  '&fval=' + val,
                                success: function(data){
                                    $("#brep").html(data);
                                }
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Module Name</label>
                        <select name="mcode" id="mcode" required class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <label>Representative Categoery</label>
                        <select name="repno" id="repno" required class="form-control">
                            <option value="R1">Rep 1</option>
                            <option value="R2">Rep 2</option>
                            <option value="R3">Rep 3</option>
                            <option value="R4">Rep 4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Batch Rep</label>
                        <select name="brep" id="brep" required class="form-control"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="8" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-info" id="updateRepMod">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteRepModModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Delete Batch Representative </h4>
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
<?php  } ?>