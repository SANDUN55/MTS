<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>
    <script>
        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                window.localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = window.localStorage.getItem('activeTab');
            if(activeTab){
                $('#MyTab a[href="' + activeTab + '"]').tab('show');
                window.localStorage.removeItem('activeTab');
            }
        });
    </script>
<body>
<?php //include 'assets/scripts/backend/database.php'; ?>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>
 <?php  include 'assets/scripts/backend/select_val.php';?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Module Management</a></li>
            <li class="active breadcrumb-item" aria-current="page">Manage Module Convener</li>
        </ol>
    </nav>
        <div class="card-header">
            <h3><i class="pe-7s-users"></i> <b> Manage Module Convener</b></h3>
            <div class="btn-actions-pane-right">
                <div class="nav" style="padding-right: 15px;" id="MyTab">
                <?php //include 'assets/scripts/backend/database.php'; ?>
<?php
database_conectivity();
$result = mysqli_query($conn,"SELECT * FROM batch WHERE batchstatus=1 ORDER BY b_no DESC ;");
$i=0;$batch=array();
while($row = mysqli_fetch_array($result)) {
    $batch[$i]=$row["b_no"]; ?>
                    <a data-toggle="tab"  href="#<?php echo $row["b_no"]; ?>" class="btn-pill btn-wide btn btn-outline-alternate btn-sm" >Batch <?php echo $row["b_no"]; ?></a>
 <?php $i++;
} ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="" role="tabpanel"><p>Please click on a Batch to display the selected Batch's defined Module Details.</p></div>
 <?php
 for ($a = 0; $a < $i; $a++) { ?>
                <div class="tab-pane" id="<?php echo $batch[$a]; ?>" role="tabpanel" >
                <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h2><b>Batch <?php echo $batch[$a]; ?></b></h2>
                                </div>
                            </div>
                        </div>
 <?php
 $total_results='';
 /*"SELECT b_no, b.m_code, m.m_name, m.m_phase, s.st_id as c1_st_id, s.t_nm as c1_t_nm, s.firstname as c1_firstname, s.surname as c1_surname, s.div_nm as c1_div_nm,
                                              s2.st_id as c2_st_id, s2.t_nm as c2_t_nm, s2.firstname as c2_firstname, s2.surname as c2_surname, s2.div_nm as c2_div_nm,
                                               ini_on, comp_on FROM batchmodule b
                                                   LEFT JOIN staff s ON cordi=s.st_id
                                                    LEFT JOIN staff s2 ON cordi2=s2.st_id
                                                    JOIN module m ON b.m_code=m.m_code
                                                    WHERE  	(comp_on='0000-00-00 00:00:00' OR comp_on IS NULL )
                                                    AND b_no=$batch[$a]
                                                    ORDER BY b_no DESC , m.m_code ASC")
 */

/* TWO CHAIR  $result = mysqli_query($conn,"SELECT b_no, b.m_code, m.m_name, m.m_phase, s.st_id as c1_st_id, s.t_nm as c1_t_nm, s.firstname as c1_firstname, s.surname as c1_surname, s.div_nm as c1_div_nm,
                                              s2.st_id as c2_st_id, s2.t_nm as c2_t_nm, s2.firstname as c2_firstname, s2.surname as c2_surname, s2.div_nm as c2_div_nm,
                                              s3.st_id as c3_st_id, s3.t_nm as c3_t_nm, s3.firstname as c3_firstname, s3.surname as c3_surname, s3.div_nm as c3_div_nm,
                                               ini_on, comp_on, en_dt, ttprogress FROM batchmodule b
                                                   LEFT JOIN staff s ON cordi=s.st_id
                                                    LEFT JOIN staff s2 ON cordi2=s2.st_id
                                                    LEFT JOIN staff s3 ON cordi3=s3.st_id
                                                    JOIN module m ON b.m_code=m.m_code
                                                    WHERE  	(comp_on='0000-00-00 00:00:00' OR comp_on IS NULL )
                                                    AND b_no=$batch[$a]
                                                    ORDER BY en_dt DESC, b_no DESC , m.m_code ASC");*/
 $result = mysqli_query($conn,"SELECT b_no, b.m_code, m.m_name, m.m_phase, s.st_id as c1_st_id, s.t_nm as c1_t_nm, s.firstname as c1_firstname, s.surname as c1_surname, s.div_nm as c1_div_nm,
                                               ini_on, comp_on, en_dt, ttprogress FROM batchmodule b
                                                   LEFT JOIN staff s ON cordi = s.st_id
                                                    JOIN module m ON b.m_code = m.m_code
                                                    WHERE  	(comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL )
                                                    AND b_no = $batch[$a] AND ttprogress <=3
                                                    ORDER BY en_dt DESC, b_no DESC , m.m_code ASC");
$total_results=mysqli_num_rows($result);
if($total_results!=''){
  ?>                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>MODULE NAME</th>
                                <th>CONVENOR</th>
                                <th></th>
                                <!--<th>CO-CHAIR</th>
                                <th></th>-->
                            </tr>
                            </thead>
                            <tbody>
 <?php
 $x=0;
 while($row = mysqli_fetch_array($result)) {
     //$editable = 0;
     //echo $row["en_dt"];
     if((   (($row["en_dt"]) >= date('Y-m-d')) OR (($row["en_dt"]) == '')) AND $row["ttprogress"] <= 1 ) {
         $editable = 1 ;
     }
     //echo $editable;
                                ?>
                                <tr id="<?php echo $row["m_code"]; ?>">
                                    <td><?php echo $x+1; ?></td>
                                    <td><?php echo $row["m_name"].', Phase '.$row["m_phase"]; ?></td>
                                    <?php
                                    $chair_dep_id=$row["c1_div_nm"];
                                    $chair_dep_nm=str_replace("Department of ","","$chair_dep_id");
                                    $chair=$row["c1_t_nm"].' '.$row["c1_firstname"].' '.$row["c1_surname"].'<hr>'.$chair_dep_nm; ?>
                                    <td><?php echo $chair; ?></td>
                                    <td align="center"><?php if($editable == 1) { ?>
                                        <a href="#editModuleModal" class="edit" data-toggle="modal">
                                            <i class="pe-7s-pen icon-gradient bg-sunny-morning update" data-toggle="tooltip"
                                               data-id1="<?php echo $batch[$a].','.$row["m_code"].',C1'; ?>"
                                               data-id2="<?php echo $batch[$a]; ?>"
                                               data-mc="<?php echo $row["m_name"].', Phase '.$row["m_phase"]; ?>"
                                               data-ma="<?php  echo  $row["c1_st_id"]; ?>"
                                               title="Edit"></i>
                                        </a><?php } ?>
                                    </td>
                                    <?php
                                   /* $chair2_dep_id=$row["c2_div_nm"];
                                    $chair2_dep_nm=str_replace("Department of ","","$chair2_dep_id");
                                    $chair2=$row["c2_t_nm"].' '.$row["c2_firstname"].' '.$row["c2_surname"].'<hr>'.$chair2_dep_nm; */?><!--
                                    <td><?php /*echo $chair2; */?></td>
                                    <td align="center"><?php /*if($editable == 1) { */?>
                                        <a href="#editModuleModal" class="edit" data-toggle="modal">
                                            <i class="pe-7s-pen icon-gradient bg-sunny-morning update" data-toggle="tooltip"
                                               data-id1="<?php /*echo $batch[$a].','.$row["m_code"].',C2'; */?>"
                                               data-id2="<?php /*echo $batch[$a]; */?>"
                                               data-mc="<?php /*echo $row["m_name"].', Phase '.$row["m_phase"]; */?>"
                                               data-ma="<?php /* echo  $row["c2_st_id"]; */?>"
                                               title="Edit"></i>
                                        </a><?php /*} */?>
                                    </td>-->
                                    <?php
  /*                                  $chair3_dep_id=$row["c3_div_nm"];
                                    $chair3_dep_nm=str_replace("Department of ","","$chair3_dep_id");
                                    $chair3=$row["c3_t_nm"].' '.$row["c3_firstname"].' '.$row["c3_surname"].'<hr>'.$chair3_dep_nm; */?><!--
                                    <td><?php /*echo $chair3; */?></td>
                                    <td align="center"><?php /*if($editable == 1) { */?>
                                        <a href="#editModuleModal" class="edit" data-toggle="modal">
                                            <i class="pe-7s-pen icon-gradient bg-sunny-morning update" data-toggle="tooltip"
                                               data-id1="<?php /*echo $batch[$a].','.$row["m_code"].',C3'; */?>"
                                               data-id2="<?php /*echo $batch[$a]; */?>"
                                               data-mc="<?php /*echo $row["m_name"].', Phase '.$row["m_phase"]; */?>"
                                               data-ma="<?php /* echo  $row["c3_st_id"]; */?>"
                                               title="Edit"></i>
                                        </a><?php /*} */?>
                                    </td>-->
                                </tr>
                                <?php
 $x++;   }
                            ?>
                            </tbody>
                        </table>
 <?php }
 else echo "<h3>Please define Modules to display!</h3>"; ?>
                    </div>
                </div>
<?php }  ?>
            </div>
        </div>
        <div class="d-block text-right card-footer">

            <a href="#addModuleModal" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> Add New Convener</a>
        </div>
</div>
			</div>		
                    </div>
                <?php include 'footer.php';
                $logAction="Load Define Module for Batch Registry";
                writeLog($logAction);
                ?>
            </div>
        </div>
    </div>
	<!-- Add Modal HTML -->
	<div id="addModuleModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Define Module in Batch </h4>
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
                            });
                        </script>
						<div class="form-group">
							<label>Module Name</label>
                            <select name="mcode" id="mcode" required class="form-control"></select>
                        </div>
                        <div class="form-group">
                            <label>Module Convener </label>
                            <?php loadAcademic(); ?>
                        </div>
                      <!--  <div class="form-group">
                            <label>Module Co-Chair </label>
                            <?php /*loadAcademic(); */?>
                        </div>-->
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
						<input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-success" id="btn-add">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript" src="assets/scripts/main.js"></script>
<script>
    $("#addModuleModal").on("show.bs.modal",function(){
        var str1 = window.localStorage.getItem('activeTab');
        var str2 = str1.substring(1);
        $('#selectBatch option[value="' + str2 +'"]').prop('selected', true);
    });
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
                    <h4 class="modal-title">Edit Chair/Co-Chair</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id1_u" name="id" class="form-control" required>
                    <div class="form-group">
                        <label>Batch </label>
                        <input type="text" id="id2_u" name="batch" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Module Name</label>
                        <input type="text" id="mc_u" name="mname" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Chair/Co-Chair</label>
                        <input type="hidden" id="mp_u" name="academic" class="form-control">
                        <?php  loadAcademic(); ?>
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
    $('#selectAcademic option[value="' + str1 +'"]').prop('selected', true);
});
</script>
<script src="assets/scripts/int_module.js"></script>
<?php  } ?>