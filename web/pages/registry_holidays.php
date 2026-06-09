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
 
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
                       
         	<div class="main-card mb-3 card">
                <div class="card-body">
             
	<?php //include '../includes/connection.php'; ?>
					 
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Registries</a></li>
            <li class="active breadcrumb-item" aria-current="page">Holidays</li>
        </ol>
    </nav>
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-date"></i> <b>Holidays Registry <?php echo date("Y"); ?></b></h2>
					</div>
					<div class="col-sm-4" align="right">
						<a href="#addHolidays" class="btn btn-success" data-toggle="modal"><i class="pe-7s-plus"></i> <span>Add New Holidays</span></a>
					</div>
                </div>
            </div>

            <?php
            //include 'assets/scripts/backend/database.php';
            database_conectivity();
            $result = mysqli_query($conn,"SELECT * FROM holidays WHERE YEAR(enddt) = YEAR(now()) OR YEAR(enddt) = (YEAR(now()) +1)  ORDER BY startDt ASC;");
            $i=1;
            $rows_per_page=15;
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
                    <th  align="center">START DATE</th>
                    <th  align="center">END DATE</th>
                    <th  align="center">DESCRIPTION</th>
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
				<tr id="<?php echo mysqli_result($result, $i, "hid"); ?>">
					<td><?php echo $i+1; ?></td>
					<td><?php echo mysqli_result($result, $i, "startDt"); ?></td>
					<td><?php echo mysqli_result($result, $i, "enddt"); ?></td>
                    <td><?php echo mysqli_result($result, $i, "holidayDes"); ?></td>
					<td align="center">
  <?php  if((mysqli_result($result, $i, "startDt")) > date('Y-m-d')) { ?>
                        <a href="#editHolModal" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning update" data-toggle="tooltip"
                               data-id="<?php echo mysqli_result($result, $i, "hid"); ?>"
                               data-mc="<?php echo mysqli_result($result, $i, "startDt"); ?>"
                               data-mn="<?php echo mysqli_result($result, $i, "enddt"); ?>"
                               data-mp="<?php echo mysqli_result($result, $i, "holidayDes"); ?>"
                               title="Edit"></i>
                        </a>
						<a href="#deleteHolModal" class="delete" data-id="<?php echo mysqli_result($result, $i, "hid"); ?>" data-toggle="modal"><i class="pe-7s-trash icon-gradient bg-strong-bliss" data-toggle="tooltip" title="Delete"></i></a>
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
                            ?><li class="pageitem <?php if($show_page == $i) { echo 'active'; } ?>" id="<?php echo $i;?>"><a href="registry_holidays.php?page=<?php echo $i; ?>" data-id="<?php echo $i;?>" class="page-link" >page <?php echo $i;?></a></li>
                            <?php
                        }
                        else{
                            ?>
                            <li class="pageitem <?php if($show_page == $i) { echo 'active'; } ?>" id="<?php echo $i;?>"><a href="registry_holidays.php?page=<?php echo $i; ?>" class="page-link" data-id="<?php echo $i;?>">page <?php echo $i;?></a></li>
                            <?php
                        }
                    }
                }
                ?>
            </ul>
            </div>
        </div>

				</div>



                    <div class="row">

                        <div class="col-sm-12" align="left">
                            <a href="#showHolidays" class="btn btn-primary" data-toggle="modal"><i class="pe-7s-eys"></i> <span>View 2022 Holidays</span></a>
                        </div>
                    </div>
			</div>
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>

	<!-- Add Modal HTML -->
	<div id="addHolidays" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form" class="needs-validation" novalidate>
					<div class="modal-header">						
						<h4 class="modal-title">Add Holidays</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">	
                        <div class="form-group">           
                            <label>Holiday start/from date</label>
                            <input type="date" id="dateSt" name="holStDate" min="<?php echo date("Y-m-d"); ?>" class="form-control" required >
                            <div class="invalid-feedback">
                                Please provide a valid date.
                            </div>
                        </div>	
                        <div class="form-group">           
                            <label>Holiday end/to date</label>
                            <input type="date" id="dateEn" name="holEnDate" min="<?php echo date("Y-m-d"); ?>"  class="form-control" required >
                            <div class="invalid-feedback">
                                Please provide a valid date.
                            </div>
                        </div>					
						<div class="form-group">
							<label>Description</label>
							<input type="text" id="hdes" name="hdes" class="form-control"  required>
                            <div class="invalid-feedback">
                                Please provide a valid description.
                            </div>
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
    $("#addHolidays").on("hidden.bs.modal",function(){
        $('#user_form')[0].reset();
    });
</script>
<!-- Edit Modal HTML -->
<div id="editHolModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Holidays</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>
                    
                    <div class="form-group">           
                            <label>Holiday start/from date</label>
                            <input type="date" id="mc_u" name="holStDate" min="<?php echo date("Y-m-d"); ?>" class="form-control" required >
                            <div class="invalid-feedback">
                                Please provide a valid date.
                            </div>
                        </div>	
                        <div class="form-group">           
                            <label>Holiday end/to date</label>
                            <input type="date" id="mn_u" name="holEnDate" min="<?php echo date("Y-m-d"); ?>"  class="form-control" required >
                            <div class="invalid-feedback">
                                Please provide a valid date.
                            </div>
                        </div>					
						<div class="form-group">
							<label>Description</label>
							<input type="text" id="mp_u" name="hdes" class="form-control"  required>
                            <div class="invalid-feedback">
                                Please provide a valid description.
                            </div>
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
<div id="deleteHolModal" class="modal fade">
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
<!--View Holidays-->
<div id="showHolidays" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title">View 2022 Holidays</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th  align="center"> DATE</th>
                                <th  align="center">DESCRIPTION</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        database_conectivity();
                        $result = mysqli_query($conn,"SELECT * FROM holidays WHERE YEAR(enddt) = 2022 ORDER BY startDt ASC;");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>". date( "d M", strtotime($row["startDt"])) ;
                            $enDt = '';
                            if($row["startDt"]==$row["enddt"])
                                    $enDt = "";
                            else
                                $enDt = " - " . date( "d M", strtotime($row["enddt"]));
                            echo $enDt . "</td>";
                            echo "<td>".$row["holidayDes"] . "</td>";
                            echo "</tr>";
                        }  ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
                </div>
            </form>
        </div>
    </div>
</div>
<?php  } ?>