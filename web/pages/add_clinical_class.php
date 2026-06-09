<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php     include 'headtag.php'; ?>
<script>
</script>
    <script src="assets/scripts/add_class.js"></script>
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
            <li class="breadcrumb-item"><a href="registry_home.php">Class Management</a></li>
            <li class="active breadcrumb-item" aria-current="page">Add Class</li>
        </ol>
    </nav>
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">ADD CLASS</h5><h4><div align="center" id="ttstatusdisplay" style="color: mediumvioletred"></div></h4>
            <form class="needs-validation" novalidate id="user_form">
                <input type="hidden" value="<?php echo $_SESSION["userMtsFom"]; ?>" id="staffID" name="staffID">
                <div class="form-row">
                    <div class="col-md-6">
                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                            <div class="position-relative form-group">
                                <label>Batch - Module</label>
                                <?php loadBatchModuleMy();?>
                                <input type="hidden" id="ttp">
                            </div>
                            <div class="position-relative form-group">
                                <label>Group</label>
                                <input name="classGroup" id="grp" placeholder="Student Group" type="text" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please provide a valid class group.
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label>Activity</label>
                                <select name="selectActivity" id="selectActivity" class="form-control" required>'
                                    <option value="" class="form-control">select Activity</option>'
                                    <option value="24" selected>Clinical Rotations</option>

                                </select>
                                <div class="invalid-feedback">
                                    Please select a Activity.
                                </div>
                            </div>

                            <div class="position-relative form-group">
                                <label>Topic</label>
                                <input name="classTopic" id="topic" placeholder="class topic" type="text" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please provide a valid class topic.
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Start Date</label>
                                    <input type="date" id="date1" name="classStDate"   class="form-control" required >
                                    <div class="invalid-feedback">
                                        Please provide a valid time.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>End Date</label>
                                    <input type="date" id="date2" name="classEnDate"   class="form-control" required >
                                    <div class="invalid-feedback">
                                        Please provide a valid time.
                                    </div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Start Time</label>
                                    <input type="time" id="start" name="classStTime"  class="form-control" min="07:00" max="17:00" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid time.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>End Time</label>
                                    <input type="time" id="endtm" name="classEnTime"  class="form-control" min="07:00" max="17:00" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid time.
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label>Lecturer</label>
                                <?php loadAcademicDepartments(1); ?>
                            </div>

                            <div class="form-group" id="diven" name="diven" style="display: block;" required="required" >
                                <label>Hospital</label>
                                <?php //loadLabs();?>
                                <select name="selectLab" id="selectLab" class="form-control" required>
                                    <option value="" class="form-control">select Hospital - Ward</option>
                                    <option value="43" selected>Base Hospital – Wathupitiwala</option>
                                    <option value="45" selected>Chest Hospital - Welisara</option>
                                    <option value="42" selected>District General Hospital – Gampaha</option>
                                    <option value="41" selected>District General Hospital – Negombo</option>
                                    <option value="40" selected>NCTH – Ragama</option>
                                    <option value="44" selected>Rehabilitation Hospital – Ragama</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <input type="hidden" value="11" name="type">
                <input type="reset" class="btn btn-secondary"  value="RESET">
                <button class="btn btn-primary" id="btn-add-clinical" >SAVE</button><!---->
            </form>
        </div>
    </div>
				</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>
<script type="text/javascript" src="assets/scripts/main.js"></script></body>

</html>
<?php } ?>