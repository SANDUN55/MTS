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
                                <label>Date</label>
                                <input type="date" id="date1" name="classDate"   class="form-control" required >
                                <div class="invalid-feedback">
                                    Please provide a valid date.
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label>Lecturer</label>
                                <?php loadAcademicDepartments(1); ?>
                            </div>
                            <script>
                                $('#date1').on('change', function() {
                                    //alert('ok');
                                    var strdt = document.getElementById("date1").value;
                                    var startTm = document.getElementById("start").value;
                                    var enTm = document.getElementById("endtm").value;
                                    var val = strdt + ' ' + startTm + ',' + strdt + ' ' + enTm;
                                    // alert(val);
                                    $.ajax({
                                        type: "POST",
                                        url: "assets/scripts/backend/get_val.php",
                                        data:'fid='+ 6 + '&fval=' + val,
                                        success: function(data){
                                            // alert(data);
                                            var staffIds = ''; var len = 0;
                                            var staffIds = data.split(',');
                                            var len = (staffIds.length) - 1;
                                            var i = 0;
                                            for (i = 0; i < len; i++) {
                                                var staffId = staffIds[i];
                                                $('#selectAcademicDep option[value="' + staffId +'"]').prop('disabled', true);
                                            }
                                        }
                                    });
                                });
                            </script>
                            <div class="position-relative form-group">
                                <input type="checkbox" id="onlinec" name="onlinec" value="0"  >
                                <label>Online Class </label>
                            </div>
                            <script>
                                $('#onlinec').on('change', function() {
                                    if(document.getElementById('onlinec').checked==true){
                                        document.getElementById('divrem').style.display = "block";
                                        document.getElementById('diven').style.display = "none";
                                        $('#diven #selectLab').prop('required', false);
                                    }else{
                                        document.getElementById('divrem').style.display = "none";
                                        document.getElementById('diven').style.display = "block";
                                        $('#divrem #remark').prop('required', false);
                                    }
                                });
                            </script>
                            <div class="form-group" id="divrem" name="divrem" style="display: none;" required="required" >
                                <label>ZOOM Link </label>
                                <input type="text" id="remark" name="remark"  class="form-control" >
                            </div>
                            <div class="form-group" id="diven" name="diven" style="display: block;" required="required" >
                                <label>Venue</label>
                                <?php loadLabs();?>
                            </div>
                            <script>
                                $('#selectLab').on('change', function() {
                                    //alert('ok');
                                    var ttval = document.getElementById("ttp").value;
                                    // alert(ttval);
                                    var val = document.getElementById("selectLab").value;
                                    var sdt = document.getElementById("date1").value;
                                    var sst = document.getElementById("start").value;
                                    var set = document.getElementById("endtm").value;
                                    var str = val + ',' + sdt + ',' + sst + ',' + set;
                                    //alert(str);
                                    $.ajax({
                                        type: "POST",
                                        url: "assets/scripts/backend/get_val.php",
                                        data:'fid='+ 10 + '&fval=' + str,
                                        success: function(data){
                                            // alert(data);
                                            if(data=='F'){
                                                alert('Can not reserve Lab. Laboratory Not Available');
                                                $('#btn-add').prop('disabled', true);
                                            }else if(data=='T'){
                                                $('#btn-add').prop('disabled', false);
                                            }
                                        }
                                    });
                                });
                            </script>




                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                            <div class="position-relative form-group">
                                <label>Batch - Module</label>
                                <?php loadBatchModuleMy();?>
                                <input type="hidden" id="ttp">
                            </div>
                            <script>
                                $('#selectBatchMo').on('change', function() {
                                    var val = document.getElementById("selectBatchMo").value;
                                    $.ajax({
                                        type: "POST",
                                        url: "assets/scripts/backend/get_val.php",
                                        data:'fid='+ 11 + '&fval=' + val,
                                        success: function(data){
                                            $('#ttstatusdisplay').html(data);
                                            $('#ttp').val(data);
                                        }
                                    });
                                });
                            </script>
                            <div class="position-relative form-group">
                                <label>Activity</label>
                                <select name="selectActivity" id="selectActivity" class="form-control" required>'
                                    <option value="" class="form-control">select Activity</option>'
                                    <option value="22">Lecture</option>
                                    <option value="23">SDL</option>
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
                </div>

                <input type="hidden" value="1" name="type">
                <input type="reset" class="btn btn-secondary"  value="RESET">
                <button class="btn btn-primary" id="btn-add" onclick="checkTime();" >SAVE</button><!---->
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