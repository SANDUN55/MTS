<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php     include 'headtag.php'; ?>

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
        <div class="card-body"><h5 class="card-title">ADD CLASS</h5>
            <form class="needs-validation" novalidate id="user_form">
                <input type="hidden" value="<?php echo $_SESSION["userMtsFom"]; ?>" id="staffID" name="staffID">
                <div class="form-row">
                    <div class="col-md-6">
                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                   <div class="position-relative form-group">
                                <label>Batch - Module</label>
                                <?php loadBatchModule();?>
                            </div>
                            <div class="position-relative form-group">
                                <label>Module</label>
                                <?php //loadModule();?>
                            </div>
                            <div class="position-relative form-group">
                                <label>Activity</label>
                                <?php loadActivity();?>
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
                            <div class="position-relative form-group">
                                <label>Date</label>
                                <input type="date" id="date1" name="classDate"   class="form-control" required >
                                <div class="invalid-feedback">
                                    Please provide a valid date.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <label>Start Time</label>
                                    <input type="time" id="start" name="classStTime"  class="form-control" min="08:00" max="17:00" required>
                                    <div class="invalid-feedback">
                                    Please provide a valid time.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" id="endtm" name="classEnTime"  class="form-control" min="08:00" max="17:00" required>
                                <div class="invalid-feedback">
                                    Please provide a valid time.
                                </div>
                            </div>
                            </div>
                            <div class="position-relative form-group">
                                <label>Lecturer</label>
                                <?php loadAcademicDepartments(); ?>
                            </div>

                            <div class="position-relative form-group">
                                <label>Venue</label>
                                <?php loadLabs();?>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <input type="hidden" value="1" name="type">
                <input type="reset" class="btn btn-default"  value="Reset">
                <button class="btn btn-primary" id="btn-add" onclick="checkTime();">SAVE</button>
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
<script src="assets/scripts/add_class.js"></script>
</html>
<?php } ?>