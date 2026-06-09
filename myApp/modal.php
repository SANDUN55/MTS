
    <?php include 'headtag.php'; ?>
    <link rel="stylesheet" href="../app/assets/fullcalendar3.4.0.css" />
    <script src="../app/assets/jquery3.2.1.min.js"></script>
    <script src="../app/assets/jquery1.12.1-ui.min.js"></script>
    <script src="../app/assets/moment2.18.1.min.js"></script>
    <script src="../app/assets/fullcalendar3.4.0.min.js"></script>
    <script src="../app/assets/bootstrap3.4.1.min.js"></script>
    <link href="../web/pages/main.css" rel="stylesheet">
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
            <li class="active breadcrumb-item" aria-current="page">Batch Representatives</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Batch Representatives Registry</b></h2>
					</div>
					<div class="col-sm-4" align="right" style="padding-bottom:10px">
						<a href="#addRepModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New </span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
						<th  align="center">BATCH NO</th>
                        <th  align="center">STUDENT NO</th>
                        <th  align="center">STUDENT NAME</th>
                        <th  align="center">WiFi USERNAME</th>
                        <th  align="center">STUDENT EMAIL</th>
						<th>ACTION</th>
                    </tr>
                </thead>
			</table>
        </div>

				</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>
<script type="text/javascript" src="main.js"></script></body>
	<!-- Add Modal HTML -->
	<div id="addRepModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_rep_form" class="needs-validation">
					<div class="modal-header">						
						<h4 class="modal-title">Add Batch Representative</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
                        <label><b>NOTE : Two batch-representatives allowed to registered into a batch </b></label>
						<div class="form-group">
                            <label>Batch </label>
                            <?php loadBatch();?>
						</div>
						<div class="form-group">
							<label>Student Number (ME/)</label>
							<input type="text" id="stno" name="stno" class="form-control" placeholder="ME/2000/000"  required>
						</div>
						<div class="form-group">
							<label>Student Index (XX/000)</label>
							<input type="text" id="ino" name="ino" class="form-control" placeholder="XX/000" required>
						</div>
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" id="nm" name="nm" class="form-control" placeholder="W D R Perera" required>
                        </div>
                        <div class="form-group">
                            <label>Kelani Net (Wifi) ID/User name</label>
                            <input type="text" id="netid" name="netid" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Student Email</label>
                            <input type="email" id="eml" name="eml" class="form-control" required>
                        </div>
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="6" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" class="btn btn-success" id="btn-add-rep">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script>
    $("#addRepModal").on("hidden.bs.modal",function(){
        $('#user_rep_form')[0].reset();
    });
</script>
<!-- Edit Modal HTML -->
<div id="editRepModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_rep_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Batch Representative</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <input type="hidden" id="id_u" name="id" class="form-control">
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="bno_u" name="bid" class="form-control">
                        <label>Batch </label>
                        <?php loadBatch();?>
                    </div>
                    <div class="form-group">
                        <label>Student Number (ME/)</label>
                        <input type="text" id="bst_no" name="stno" class="form-control" placeholder="ME/2000/000"  required>
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Student Index (XX/000)</label>
                        <input type="text" id="bst_in" name="ino" class="form-control" placeholder="XX/000" required>
                    </div>
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" id="bst_nm" name="nm" class="form-control" placeholder="W D R Perera" required>
                    </div>
                    <div class="form-group">
                        <label>Kelani Net (Wifi) ID/User name</label>
                        <input type="text" id="bst_nt" name="netid" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Student Email</label>
                        <input type="email" id="bst_em" name="eml" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="7" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-info" id="btn-update-rep">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <script>
     $("#editRepModal").on("show.bs.modal",function(){
         var str1 = document.getElementById("bno_u").value;
         $('#selectBatch option[value="' + str1 +'"]').prop('selected', true);
     });

 </script>
<!-- Disable Modal HTML -->
<div id="disableBatchModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Disable Batch Representative</h4>
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
</html>
