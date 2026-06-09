<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
<?php include 'headtag.php'; ?>
    <script src="assets/scripts/int_module.js"></script>
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

<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Module User Registry</a></li>
            <li class="active breadcrumb-item" aria-current="page">Timetable Manager</li>
        </ol>
    </nav>
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Timetable Manager Registry</b></h2>
					</div>
                </div>
            </div>

<?php
include 'assets/scripts/backend/database.php';
//database_conectivity();
$batchModArr = array();
$chair=$_SESSION["userMtsFom"];
$result = mysqli_query($conn, "SELECT CONCAT( b_no, '-' , m_code ) as val FROM `batchmodule` WHERE cordi IN (SELECT st_id FROM staff WHERE username='$chair' ) 
              OR cordi2 IN (SELECT st_id FROM staff WHERE username='$chair' ) ; ") or die(mysqli_error($conn));
$i=0;
while($row = mysqli_fetch_array($result)) {
    $batchModArr[$i] = $row["val"];
    $i++;
}
//print_r($batchModArr);
$result = mysqli_query($conn," SELECT b_no, m.m_code, CONCAT( b_no, '-' , m.m_code ) as val, CONCAT (m_name, ' ', m_phase) as mod_nm,
cordi, CONCAT (c1.t_nm, ' ', c1.firstname , ' ', c1.surname) as cordi_nm ,
cordi2, CONCAT (c2.t_nm, ' ', c2.firstname , ' ', c2.surname) as cordi2_nm ,
ttmng1 , CONCAT (t1.t_nm, ' ', t1.firstname , ' ', t1.surname, '<hr>', t1.div_nm) as ttmng1_nm,
ttmng2, CONCAT (t2.t_nm, ' ', t2.firstname , ' ', t2.surname, '<hr>', t2.div_nm) as ttmng2_nm,
ttmng3, CONCAT (t3.t_nm, ' ', t3.firstname , ' ', t3.surname, '<hr>', t3.div_nm) as ttmng3_nm,
ttmng4, CONCAT (t4.t_nm, ' ', t4.firstname , ' ', t4.surname, '<hr>', t4.div_nm) as ttmng4_nm,
ttmng5, CONCAT (t5.t_nm, ' ', t5.firstname , ' ', t5.surname, '<hr>', t5.div_nm) as ttmng5_nm,
ttmng6, CONCAT (t6.t_nm, ' ', t6.firstname , ' ', t6.surname, '<hr>', t6.div_nm) as ttmng6_nm
FROM batchmodule LEFT JOIN staff c1 ON cordi=c1.st_id
LEFT JOIN staff c2 ON cordi2=c2.st_id
LEFT JOIN staff t1 ON ttmng1=t1.st_id
LEFT JOIN staff t2 ON ttmng2=t2.st_id
LEFT JOIN staff t3 ON ttmng3=t3.st_id
LEFT JOIN staff t4 ON ttmng4=t4.st_id
LEFT JOIN staff t5 ON ttmng5=t5.st_id
LEFT JOIN staff t6 ON ttmng6=t6.st_id
LEFT JOIN module m ON batchmodule.m_code = m.m_code
WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL) 
ORDER BY b_no DESC , m_phase, m_name ;");
?>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th  align="center">BATCH NO</th>
                    <th  align="center">BATCH NAME</th>
                    <th  align="center">CHAIR</th>
                    <th  align="center">CO-CHAIR</th>
                    <th>TT MNG1</th>
                    <th> </th>
                    <th>TT MNG2</th>
                    <th> </th>
                    <th>TT MNG3</th>
                    <th> </th>
                    <th>TT MNG4</th>
                    <th> </th>
                    <th>TT MNG5</th>
                    <th> </th>
                    <th>TT MNG6</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
<?php
$x=0; $editOn = 0;
while($row = mysqli_fetch_array($result)) {
    $val = $row["val"];
    if(in_array($val, $batchModArr)) {
        $editOn = 1;
    }else {
        $editOn = 0;
    }
    ?>
				<tr id="<?php echo $row["b_no"]; ?>"><!-- echo $row["b_no"]; -->
                    <td><?php echo $row["b_no"]; ?></td>
					<td><?php echo $row["mod_nm"]; ?></td>
					<td><?php echo $row["cordi_nm"]; ?></td>
                    <td align="center"><?php echo $row["cordi2_nm"]; ?></td>
                    <td align="center"><?php echo $row["ttmng1_nm"]; ?></td>
                    <td align="center"> <?php if($editOn == 1) {  ?>
                        <a href="#editTTmngModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning updatett" data-toggle="tooltip"
                               data-id1="<?php echo $val.',T1'; ?>"
                               data-bn="<?php echo $row["b_no"]; ?>"
                               data-mc="<?php echo $row["mod_nm"]; ?>"
                               title="Edit"></i>
                        </a>
                         <a href="#deleteTTmngModal" class="delete" data-id="<?php echo $val.',T1'; ?>" data-toggle="modal">
                             <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                         </a><?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["ttmng2_nm"]; ?></td>
                    <td align="center"> <?php if($editOn == 1) {  ?>
                        <a href="#editTTmngModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning updatett" data-toggle="tooltip"
                               data-id1="<?php echo $val.',T2'; ?>"
                               data-bn="<?php echo $row["b_no"]; ?>"
                               data-mc="<?php echo $row["mod_nm"]; ?>"
                               title="Edit"></i>
                        </a>
                        <a href="#deleteTTmngModal" class="delete" data-id="<?php echo $val.',T2'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                         </a><?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["ttmng3_nm"]; ?></td>
                    <td align="center"> <?php if($editOn == 1) {  ?>
                            <a href="#editTTmngModal" class="edit" data-toggle="modal">
                                <i class="pe-7s-pen icon-gradient bg-sunny-morning updatett" data-toggle="tooltip"
                                   data-id1="<?php echo $val.',T3'; ?>"
                                   data-bn="<?php echo $row["b_no"]; ?>"
                                   data-mc="<?php echo $row["mod_nm"]; ?>"
                                   title="Edit"></i>
                            </a> </a>
                            <a href="#deleteTTmngModal" class="delete" data-id="<?php echo $val.',T3'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                            </a><?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["ttmng4_nm"]; ?></td>
                    <td align="center"> <?php if($editOn == 1) {  ?>
                            <a href="#editTTmngModal" class="edit" data-toggle="modal">
                                <i class="pe-7s-pen icon-gradient bg-sunny-morning updatett" data-toggle="tooltip"
                                   data-id1="<?php echo $val.',T4'; ?>"
                                   data-bn="<?php echo $row["b_no"]; ?>"
                                   data-mc="<?php echo $row["mod_nm"]; ?>"
                                   title="Edit"></i>
                            </a>
                            <a href="#deleteTTmngModal" class="delete" data-id="<?php echo $val.',T4'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                            </a><?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["ttmng5_nm"]; ?></td>
                    <td align="center"> <?php if($editOn == 1) {  ?>
                            <a href="#editTTmngModal" class="edit" data-toggle="modal">
                                <i class="pe-7s-pen icon-gradient bg-sunny-morning updatett" data-toggle="tooltip"
                                   data-id1="<?php echo $val.',T5'; ?>"
                                   data-bn="<?php echo $row["b_no"]; ?>"
                                   data-mc="<?php echo $row["mod_nm"]; ?>"
                                   title="Edit"></i>
                            </a>
                            <a href="#deleteTTmngModal" class="delete" data-id="<?php echo $val.',T5'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                            </a><?php  } ?>
                    </td>
                    <td align="center"><?php echo $row["ttmng6_nm"]; ?></td>
                    <td align="center"> <?php if($editOn == 1) {  ?>
                            <a href="#editTTmngModal" class="edit" data-toggle="modal">
                                <i class="pe-7s-pen icon-gradient bg-sunny-morning updatett" data-toggle="tooltip"
                                   data-id1="<?php echo $val.',T6'; ?>"
                                   data-bn="<?php echo $row["b_no"]; ?>"
                                   data-mc="<?php echo $row["mod_nm"]; ?>"
                                   title="Edit"></i>
                            </a>
                            <a href="#deleteTTmngModal" class="delete" data-id="<?php echo $val.',T6'; ?>" data-toggle="modal">
                                <i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i>
                            </a><?php  } ?>
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
<div id="editTTmngModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Timetable Manager</h4>
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
                        <label>Timetable Manager</label>
                        <input type="hidden" id="mp_u" name="academic" class="form-control">
                        <?php  loadNonAcademic(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="3" name="type">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-info" id="updatett">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteTTmngModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>

                <div class="modal-header">
                    <h4 class="modal-title">Delete Timetable Manager </h4>
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