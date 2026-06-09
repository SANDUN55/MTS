<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>

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
            <li class="active breadcrumb-item" aria-current="page">Add Group Class</li>
        </ol>
    </nav>
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">ADD  GROUP CLASS</h5>
            <form class="needs-validation" novalidate id="user_form">


                        <fieldset class="border p-2">
                            <legend  class="w-auto"></legend>
                                 <div class="position-relative form-group">
                                    <label>Batch - Module</label>
                                    <?php loadBatchModule();?>
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
                            <input type="hidden" id="classType" name="classType"  >
                        </fieldset>

<script>
    $('#selectBatchMo').on('change', function() {
        var val = this.value;
        //alert(val);
        //SET MIN AND MAX FOR DURATION
        $.ajax({
            type: "POST",
            url: "assets/scripts/backend/get_val.php",
            data:'fid='+ 5 + '&fval=' + val,
            success: function(data){
                //2020-11-02,2020-12-31
                //alert(data);
                var Dt = data.split(',');
                var tdt = new Date();

                var minDt = Dt[0];
                var maxDt = Dt[1];
                //var minDtJ = new Date(minDt);
                //var maxDtJ = new Date(maxDt);
                //if((tdt > minDtJ) && (tdt < maxDtJ)){
                   // minDt = tdt;
               // }

                //alert(minDt);
                $("#grpdetails #activityDate1").attr( 'min', minDt) ;
                $("#grpdetails #activityDate1").attr( 'max', maxDt) ;
                $("#nonGroupDetails #date1").attr( 'min', minDt) ;
                $("#nonGroupDetails #date1").attr( 'max', maxDt) ;
            }
        });

    });
    $('#selectActivity').on('change', function() {
        var acode = this.value;
        var bacthMod = document.getElementById("selectBatchMo").value;
        var str = bacthMod.replace('-',',');
        var val= str + ',' + acode;
       //alert(val);
        $.ajax({
            type: "POST",
            url: "assets/scripts/backend/get_val.php",
            data:'fid='+ 4 + '&fval=' + val,
            success: function(data){
                //alert(data);
                var actinfo = data.split(',');
                var grpCode = actinfo[0];
                var actNo = actinfo[1];
                $("#classType").val(grpCode);
                if(grpCode == 'G'){
                    $("#grpdetails").show();
                    $("#nonGroupDetails").hide();
                    $('#grpdetails #divRepeat').hide();
                    $('#grpdetails #activity-no').val(actNo);
                    $('#nonGroupDetails #date1').prop('required', false);
                    $('#nonGroupDetails #start').prop('required', false);
                    $('#nonGroupDetails #endtm').prop('required', false);
                    $('#nonGroupDetails #selectAcademicDep').prop('required', false);
                    $('#nonGroupDetails #selectLab').prop('required', false);
                }else if(grpCode == 'N'){
                    $("#grpdetails").hide();
                    $("#nonGroupDetails").show();
                    $('#nonGroupDetails #selectAcademicDep').prop('required', true);
                    $('#nonGroupDetails #selectLab').prop('required', false);
                    $('#grpdetails #group-count').prop('required', false);
                    $('#grpdetails #activityGroup').prop('required', false);
                    $('#grpdetails  #activityDate1').prop('required', false);
                    $('#grpdetails  #activityStart').prop('required', false);
                    $('#grpdetails #activityEndtm').prop('required', false);
                    $('#grpdetails #selectLab').prop('required', false);
                }

            }
        });
    });
</script>
<div id="grpdetails" style="background-color: #3AD5A7">
<hr>
    <div class="row" >
        <input name="activity-no" id="activity-no"  type="text">
        <div class="col-sm">
            <label>Group Count</label>
            <input name="group-count" id="group-count"  type="number" min="1" max="8"  class="form-control" required >
        </div>
        <div class="col-sm">
            <label>Add Rows</label>
            <button class="btn btn-danger" id = "addMore" >+ rows</button>
        </div>
    </div>
    <div id="divRepeat">
        <div class="row" id = "actvity_class" style="background-color: #00A6C7">
            <div class="col">
                <label>Group Name</label>
                <input type="text" id="activityGroup" name="activityGroup[]"   class="form-control" required >
                <div class="invalid-feedback">
                    Please provide a valid date.
                </div>
            </div>

            <div class="col">
                <label>Date</label>
                <input type="date" id="activityDate1" name="activityDate[]"  min="2020-01-01" max="2020-04-31" class="form-control" required >
                <div class="invalid-feedback">
                    Please provide a valid date.
                </div>
            </div>
            <div class="col">
                <label>Start Time</label>
                <input type="time" id="activityStart" name="activityStTime[]"  class="form-control" min="08:00" max="17:00" required>
                <div class="invalid-feedback">
                    Please provide a valid time.
                </div>
            </div>
            <div class="col">
                <label>End Time</label>
                <input type="time" id="activityEndtm" name="activityEnTime[]"  class="form-control" min="08:00" max="17:00" required>
                <div class="invalid-feedback">
                    Please provide a valid time.
                </div>
            </div>
            <div class="col">
                <label>Venue</label>
                <?php loadLabs();?>
            </div>

        </div>
    </div>
    <hr>
</div>

<script>
 $(document).on('click','#addMore',function(e) {
     // $("#actvity_class").append($("#actvity_class").html());
     $('#grpdetails #divRepeat').show();
     var groupCount = document.getElementById("group-count").value;
     //alert(groupCount);
     for (i = 1; i < groupCount; i++) {
         //alert(i);
         //$("#divRepeat").append($("#actvity_class").html());
         $("#divRepeat").append($("#actvity_class").clone());
    }

 });
</script>


<div id = "nonGroupDetails">
        <fieldset class="border p-2">
            <legend  class="w-auto"></legend>
            <div class="position-relative form-group">
                <label>Date</label>
                <input type="date" id="date1" name="classDate"  min="2020-01-01" max="2020-12-31" class="form-control" required >
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
                <?php loadAcademicDepartments(1); ?>
            </div>
            <div class="position-relative form-group">
                <label>Venue</label>
                <?php loadLabs();?>
            </div>
        </fieldset>
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
<script>
    $(document).ready(function() {
        $("#grpdetails").hide();
        $("#nonGroupDetails").hide();
    });

</script>
<script type="text/javascript" src="assets/scripts/main.js"></script></body>
<script src="assets/scripts/add_class.js"></script>
</html>
<?php } ?>