<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>
    <?php  include 'assets/scripts/backend/select_val.php';?>
<body>
<script src="assets/scripts/add_class.js"></script>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner" style="height: 80%">
                <div class="row">
                    <div class="col">
                        <nav class="" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="registry_home.php">Module Timetable</a></li>
                                <li class="active breadcrumb-item" aria-current="page">Import Classes from Previous Batch <?php print_r($_SESSION); ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <form class="needs-validation" novalidate id="user_form">
                    <div class="row">
                        <div class="col">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">IMPORT CLASSES FROM PREVIOUS BATCH</h5>
                                    <!--<div class="position-relative form-group">
                                        <label>Batch</label>
                                        <?php
/*                                        database_conectivity();
                                        $result = mysqli_query($conn,"SELECT MAX(b_no) AS bno FROM batch WHERE batchstatus = 1;");
                                        $row = mysqli_fetch_array($result);
                                        $batch = $row['bno'] - 1;
                                        */?>
                                        <input type = "text" id="batch" name="batch"   class = "form-control" value="<?php /* echo $batch;   */?>" required >
                                    </div>-->
                                    <div class="position-relative form-group">
                                        <label>Select Current Batch Module</label>
                                        <?php database_conectivity();loadBatchModuleMy();?>
                                        <input type="hidden" id="ttp">
                                    </div>
                                    <fieldset class="border p-2">
                                        <legend  class="w-auto">Group Classes Data will not be Imported  </legend>
                                        <div class="position-relative form-group">
                                            <label>Import to Date (New Batch Start Date)</label>
                                            <input type="date" id="date1" name="classDate"  class="form-control" required >
                                            <div class="invalid-feedback">
                                                Please provide a valid date.
                                            </div>
                                        </div>
                                        <p>NOTE : PLEASE CHECK PUBLIC/FACULTY HOLIDAYS ARE CORRECTLY MARKED BEFORE IMPORTING.</p>
                                        <input type="hidden" value="7" name="type">
                                        <input type="reset" class="btn btn-default"  value="Reset">
                                        <button class="btn btn-primary" id="btn-impc" >IMPORT CLASSES</button>
                                        <p></p><p></p><p></p><p></p><p></p>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <script>
                        </script>
                        <div class="col" id="displayTT">
                            <iframe id="myIframe" src="timetableDisplay.php?id=<?php echo $getVal; ?>" height="100%"  width="100%" scrolling="yes" frameborder="0" src = ></iframe>
                        </div>
                    </div>
                </form>
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/scripts/main.js"></script></body>
</html>
<?php } ?>