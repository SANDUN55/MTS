<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>
    <script src="assets/scripts/module.js"></script>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>
 <?php  include 'assets/scripts/backend/select_val.php';?>
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
            <li class="active breadcrumb-item" aria-current="page">Module</li>
        </ol>
    </nav>
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-settings"></i> <b>Module Registry</b></h2>
					</div>
					<div class="col-sm-4" align="right">
						<a href="#addModuleModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New Module</span></a>
					</div>
                </div>
            </div>
                <?php
                database_conectivity();
                $result = mysqli_query($conn,"SELECT m.*, s.sn_abr, s.sn_nm, s.sn_id FROM module as m JOIN strand as s ON m.m_strand=s.sn_id ORDER BY m.m_phase, m.m_name ASC;");
                $i=1;
                $rows_per_page=10;
                $total_results=mysqli_num_rows($result);
                $total_pages=ceil($total_results / $rows_per_page);
                $show_page='';
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $show_page = $_GET['page'];
                    if ($show_page > 0 && $show_page <= $total_pages) {
                        $start = ($show_page -1) * $rows_per_page;
                        $end = $start + $rows_per_page;  }
                    else {
                        $start = 0;
                        $end = $rows_per_page; }
                }
                else{
                    $start = 0;
                    $end = $rows_per_page; }
                ?>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th  align="center">MODULE CODE</th>
                    <th  align="center">MODULE NAME</th>
                    <th  align="center">PHASE</th>
                    <th  align="center">STRAND</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
<?php
//while($row = mysqli_fetch_array($result)) {
if (!function_exists('mysqli_result')) {
    function mysqli_result($res, $row, $field=0) {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }
}
for ($i = $start; $i < $end; $i++){
    if ($i == $total_results) { break; }
?>
				<tr id="<?php echo mysqli_result($result, $i, "m_code"); ?>">
					<td><?php echo $i+1; ?></td>
					<td><?php echo mysqli_result($result, $i, "m_code"); ?></td>
					<td><?php echo mysqli_result($result, $i, "m_name"); ?></td>
                    <td align="center"><?php echo mysqli_result($result, $i, "m_phase"); ?></td>
                    <td align="center"><span data-toggle="tooltip" data-placement="top" title="<?php echo mysqli_result($result, $i, "sn_nm"); ?>"><?php echo mysqli_result($result, $i, "sn_abr"); ?></span></td>
                    <td align="center">
 <?php
 if(mysqli_result($result, $i, "m_st")==0) echo "Disable";
 if(mysqli_result($result, $i, "m_st")==1) echo "Active";
 ?>
                    </td>
					<td align="center">
  <?php  if(mysqli_result($result, $i, "m_st")) { ?>
                        <a href="#editModuleModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning update" data-toggle="tooltip"
                               data-id="<?php echo mysqli_result($result, $i, "m_code"); ?>"
                               data-mc="<?php echo mysqli_result($result, $i, "m_code"); ?>"
                               data-mn="<?php echo mysqli_result($result, $i, "m_name"); ?>"
                               data-mp="<?php echo mysqli_result($result, $i, "m_phase"); ?>"
                               data-ms="<?php echo mysqli_result($result, $i, "sn_id"); ?>"
                               title="Edit"></i>
                        </a>
						<a href="#deleteModuleModal" class="delete" data-id="<?php echo mysqli_result($result, $i, "m_code"); ?>" data-toggle="modal"><i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i></a>
                        <a href="#disableModuleModal" class="disable" data-id="<?php echo mysqli_result($result, $i, "m_code"); ?>" data-toggle="modal"><i class="pe-7s-close-circle  icon-gradient bg-grow-early" data-toggle="tooltip" title="Disable"></i></a>
                        <?php } ?>
                    </td>
				</tr>
				<?php
				}

				?>
				</tbody>
			</table>
            <div>
            <ul class="pagination">
                <?php
                if(!empty($total_pages)){
                    for($i=1; $i<=$total_pages; $i++){
                        if($i == 1){
                            ?><li class="pageitem <?php if($show_page == $i) { echo 'active'; } ?>" id="<?php echo $i;?>"><a href="registry_module.php?page=<?php echo $i; ?>" data-id="<?php echo $i;?>" class="page-link" >Page <?php echo $i;?> </a></li>
                            <?php
                        }
                        else{
                            ?>
                            <li class="pageitem <?php if($show_page == $i) { echo 'active'; } ?>" id="<?php echo $i;?>"><a href="registry_module.php?page=<?php echo $i; ?>" class="page-link" data-id="<?php echo $i;?>">Page <?php echo $i;?>  ></a></li>
                            <?php
                        }
                    }
                }
                ?>
            </ul>
               <div style="text-align: right;"> NOTE: Mouse over onto the Strand Abbreviation to expand </div>
            </div>
        </div>

				</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>

	<!-- Add Modal HTML -->
	<div id="addModuleModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Add Module</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Module Code (four characters code)</label>
                            <input type="text" id="mcode" name="mcode" class="form-control" required
                                   oninvalid="this.setCustomValidity('Please enter valid code')"
                                   oninput="this.setCustomValidity('')"
                                   >
							<!--<input type="text" id="mcode" name="mcode" class="form-control"
                                   pattern="[A-Za-z]{4}" required
                                   oninvalid="this.setCustomValidity('Please enter valid code')"
                                   oninput="this.setCustomValidity('')"
                                   >-->
						</div>
						<div class="form-group">
							<label>Module Name</label>
						    <input type="text" id="mname" name="mname" class="form-control" required
                                   oninvalid="this.setCustomValidity('Please enter valid name')"
                                   oninput="this.setCustomValidity('')"
                            >
						</div>
						<div class="form-group">
							<label>Phase</label>
                            <?php  loadPhase(); ?>
                        </div>
                        <div class="form-group">
                            <label>Strand</label>
                            <?php  loadStrand(); ?>
                        </div>
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
<script type="text/javascript" src="assets/scripts/main.js"></script>
<script>
    $("#addModuleModal").on("hidden.bs.modal",function(){
        $('#user_form')[0].reset();
    });
</script>
<!-- Edit Modal HTML -->
<div id="editModuleModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Module</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>
                    <div class="form-group">
                        <label>Module Code</label>
                        <input type="text" id="mc_u" name="mcode" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Module Name</label>
                        <input type="text" id="mn_u" name="mname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phase</label>
                        <input type="hidden" id="mp_u" name="mphase" class="form-control">
                        <?php  loadPhase(); ?>

                    </div>
                    <div class="form-group">
                        <label>Strand</label>
                        <input type="hidden" id="ms_u" name="mstrand" class="form-control">
                        <?php  loadStrand(); ?>

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
<script>
$("#editModuleModal").on("show.bs.modal",function(){
    var str1 = document.getElementById("mp_u").value;
    $('[name=selectPhase]').val( str1 );
   var str2 = document.getElementById("ms_u").value;
    $('[name=selectStrand]').val( str2 );
});
</script>
<!-- Delete Modal HTML -->
<div id="deleteModuleModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Delete Module</h4>
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
<div id="disableModuleModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Disable Module</h4>
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
<?php  } ?>