<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
    {
        header('Location:../login.php');
        die();
    }else {
?>
    <?php include 'headtag.php'; ?>
    <script src="assets/scripts/registries.js"></script>
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
            <li class="breadcrumb-item"><a href="registry_home.php">User Management</a></li>
            <li class="active breadcrumb-item" aria-current="page">Visiting Staff</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-users"></i> <b>Visiting Staff Registry</b></h2>
					</div>
					<div class="col-sm-4" align="right" style="padding-bottom:10px">
						<a href="#addVisitingStaff" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New </span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
						<th>NAME</th>
                        <th>DEPARTMENT</th>
                        <th  align="center">STAFF CODE</th>
                        <th  align="center">EMAIL </th>
                        <th  align="center">ACTION</th>
                    </tr>
                </thead>
				<tbody>
				<?php
                database_conectivity();
               	$result = mysqli_query($conn,"SELECT CONCAT(t_nm, ' ', firstname, ' ', surname) as sname, st_em, st_id, firstname, surname, staffcode, dep_code, div_nm, username   FROM staff WHERE st_cat = 4 AND onleave = '0' ;");
                $i=1; $stcode = '';
				while($row = mysqli_fetch_array($result)) {
                    $stcode = $row["staffcode"];
				?>
				<tr id="<?php echo $row["st_id"]; ?>">
					<td><?php echo $i; ?></td>
					<td><?php echo $row["sname"]; ?></td>
                    <td><?php echo $row["div_nm"]; ?></td>
                    <td><?php echo $row["staffcode"]; ?></td>
                    <td><?php echo $row["st_em"]; ?></td>
					<td>
                        <a href="#editVisitingStaff" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning updateVS" data-toggle="tooltip"
                               data-id="<?php echo $row["st_id"]; ?>"
                               data-fnm="<?php echo $row["firstname"]; ?>"
                               data-snm="<?php echo $row["surname"]; ?>"
                               data-eml="<?php echo $row["st_em"]; ?>"
                               data-stc="<?php echo $row["staffcode"]; ?>"
                               data-std="<?php echo $row["dep_code"]; ?>"
                               data-unm="<?php echo $row["username"]; ?>"
                               title="Edit"></i>
                        </a>
						 <a href="#disableVisitingStaff" class="disableVS" data-id="<?php echo $row["st_id"]; ?>" data-toggle="modal"><i class="pe-7s-close-circle  icon-gradient bg-happy-itmeo" data-toggle="tooltip" title="Disable"></i></a>
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
	<div id="addVisitingStaff" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_vs_form" class="needs-validation">
					<div class="modal-header">						
						<h4 class="modal-title">Add Visiting Staff</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
                            <label>Title </label>
                            <select class="form-control" name="title" id="title">
                                <option value="Dr">Dr</option>
                                <option value="Professor">Professor</option>
                                <option value="Ms">Ms</option>
                                <option value="Ms">Ms</option>
                            </select>
						</div>
						<div class="form-group">
							<label>First Name</label>
							<input type="text" id="fname" name="fname" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Surname</label>
							<input type="text" id="sname" name="sname" class="form-control" required>
						</div>
                        <div class="form-group">
                            <label>Department</label><?php
                            database_conectivity();
                            $result = mysqli_query($conn,"SELECT div_id, div_nm FROM divisions WHERE div_cat IN (2, 1, 4) ORDER BY div_nm  ASC;");
                            ?>
                            <select name="depart" id="depart" class="form-control" required>
                                <option value="">select department</option>
                             <?php   while($row = mysqli_fetch_array($result)) {
                                echo '<option value="' . $row["div_id"] . '">' . $row["div_nm"].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Staff Code</label>
                            <input type="text" id="stcode" name="stcode" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kelani NET ID    <i><b> * DDS VISITING STAFF ONLY *</i></b></label>
                            <input type="text" id="netid" name="netid" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="eml" name="eml" class="form-control" required>
                        </div>
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="5" name="type">
                        <?php
                        //GET MODULE DEPARTMENT
                       /* database_conectivity();
                        $dep = '';
                        $result = mysqli_query($conn,"SELECT dep_code FROM staff WHERE username = '$_SESSION[userMtsFom]' ;");
                        if($row = mysqli_fetch_array($result)) {
                            $dep = $row["dep_code"];
                        }*/
                        ?>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" class="btn btn-success" id="btn-add-visiting-staff">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script>
    $("#addVisitingStaff").on("hidden.bs.modal",function(){
        $('#user_vs_form')[0].reset();
    });
</script>
<!-- Edit Modal HTML -->
<div id="editVisitingStaff" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_vs_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Visiting Staff</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <input type="hidden" id="id_u" name="id" class="form-control">
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title </label>
                        <select class="form-control" name="title" id="title">
                            <option value="Dr">Dr</option>
                            <option value="Professor">Professor</option>
                            <option value="Ms">Ms</option>
                            <option value="Ms">Ms</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="mf_u" name="fname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Surname</label>
                        <input type="text" id="ms_u" name="sname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <input type="hidden" id="md_u" name="md_u" class="form-control" required>
                        <?php
                        database_conectivity();
                        $result = mysqli_query($conn,"SELECT div_id, div_nm FROM divisions WHERE div_cat IN (2, 1, 7, 4) ORDER BY div_nm  ASC;");
                        ?>
                        <select name="depart" id="depart" class="form-control" required>
                            <option value="" class="form-control">select department</option>
                            <?php   while($row = mysqli_fetch_array($result)) {
                                echo '<option value="' . $row["div_id"] . '">' . $row["div_nm"].'</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Staff Code</label>
                        <input type="text" id="st_u" name="stcode" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kelani NET ID   <i><b> * DDS VISITING STAFF ONLY *</i></b>  </label>
                        <input type="text" id="nt_u" name="netid" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="me_u" name="eml" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="6" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-info" id="btn-update-visiting-staff">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
        $("#editVisitingStaff").on("show.bs.modal",function(){
            var str1 = document.getElementById("md_u").value;
            $('#depart option[value="' + str1 +'"]').prop('selected', true);
        });
    </script>
<!-- Disable Modal HTML -->
<div id="disableVisitingStaff" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title">Disable Visiting Staff</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_vd" name="id" class="form-control">
                    <p>Are you sure you want to <span class="text-danger">DISABLE</span> this Record?</p>
                    <p class="text-primary"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="disable-visiting-staff">Disable</button>
                </div>
            </form>
        </div>
    </div>
</div>
</html>
<?php } ?>