<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
}else {
?>
<?php include 'headtag.php'; ?>
<?php include 'header-top.php'; ?>
    <title>Module Timetable Calender</title>
    <!--
    <link rel="stylesheet" href="../assets/scripts/calander/fullcalendar.css" />

    <script src="assets/scripts/jquery1.12.4.min.js"> </script>
    <script src="assets/scripts/calander/2.18.1moment.min.js"></script>
    <script src="assets/scripts/calander/3.4.0fullcalendar.min.js"></script>
    <link rel="stylesheet" href="assets/scripts/calander/3.4.1bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="assets/scripts/calander/3.4.1bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="assets/scripts/timetable.js"></script>
<?php  include 'assets/scripts/backend/select_val.php';?>

<div class="app-main">
<?php include 'navbar-left.php'; ?>
<div class="app-main__outer">
 <div class="app-main__inner">
     <div class="main-card mb-3 card">
     <div class="card-body">
     <div class="container">
     <nav class="" aria-label="breadcrumb">
 <?php
 $batchhMod = array(); $getVal='';
    if($_GET['id']) {
        $getVal = $_GET['id'];
        $batchhMod = explode('-', $getVal);
         }
         ?>
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="registry_home.php">Calender</a></li>
             <li class="breadcrumb-item"><a href="registry_home.php">Timetable</a></li>
             <li class="active breadcrumb-item" aria-current="page"><h4 align="center"> Batch <?php  echo $batchhMod[0]. ' - ' . $batchhMod[2]. ' Module' ; ?>  </h4></li>
         </ol>

     </nav>
     </div>

          <div class="container">
     <div id="calendar"></div>
 <div>
     <div align="center" style="padding-bottom: 1.5em;">
         <a href="#confirmTimetableModal" class="btn btn-primary" data-id="<?php echo $getVal;  ?>" data-toggle="modal">Print</a>
     </div>
 </div>
 </div>
 </div>

     </div>
 </div>

<?php include 'footer.php'; ?>
            </div>
</div>



<!-- Add Modal -->
<div id="createEventModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add Class on <div id="modalWhen" style="margin-top:5px;"></div></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div id="modalBody" class="modal-body">
                <form class="needs-validation" novalidate id="user_form">
                    <input type="hidden" id="getBatch" name="getBatch"  value="<?php echo $batchhMod[0]; ?>" >
                    <input type="hidden" id="getMod" name="getMod"  value="<?php echo $batchhMod[1]; ?>" >
                    <div class="form-group">
                        <label>Batch </label>
                        <?php loadBatch();?>
                    </div>
                    <div class="form-group">
                        <label>Module  </label>
                        <?php loadModule();?>
                    </div>
                    <div class="form-group">
                        <label>Activity</label>
                        <?php loadActivity();?>
                    </div>
                    <div class="form-group">
                        <label>Class Topic</label>
                        <input name="classTopic" id="topic" placeholder="class topic" type="text" class="form-control" required>
                        <div class="invalid-feedback">
                            Please provide a valid class topic.
                        </div>
                        <input type="text" id="classType" name="classType"  >
                    </div>



<script>
    $('#selectActivity').on('change', function() {
        var acode = this.value;
        var bacth = document.getElementById("getBatch").value;
        var modn = document.getElementById("getMod").value;
        var val= bacth + ',' + modn + ',' + acode;
       // alert(val);
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
                //$("#grpdetails").html(data);
                if(grpCode == 'G'){
                    $("#grpdetails").show();
                    $("#nonGroupDetails").hide();
                    //alert(actNo);
                    $('#grpdetails #activity-no').val(actNo);
                    $('#nonGroupDetails #selectAcademicDep').prop('required', false);
                    $('#nonGroupDetails #selectLab').prop('required', false);

                }else if(grpCode == 'N'){
                    $("#grpdetails").hide();
                    $("#nonGroupDetails").show();
                    $('#nonGroupDetails #selectAcademicDep').prop('required', true);
                    $('#nonGroupDetails #selectLab').prop('required', true);
                    $('#grpdetails #group').prop('required', false);
                    $('#grpdetails  #date1').prop('required', false);
                    $('#grpdetails  #start').prop('required', false);
                    $('#grpdetails #endtm').prop('required', false);
                    $('#grpdetails #group-count').prop('required', false);
                }

            }
        });
    });
</script>

<div id="grpdetails" style="background-color: #3AD5A7">
    <hr>

    <div class="row" >

        <div class="col-sm">
            <label>Activity Number</label>
            <input name="activity-no" id="activity-no"  type="text" class="form-control" required>
            <div class="invalid-feedback">
                Please provide a valid Activity No.
            </div>
        </div>
        <div class="col-sm">
            <label>Group Count</label>
            <input name="group-count" id="group-count"  type="number" min="1" max="8"  class="form-control" required>
        </div>
        <div class="col-sm">
            <label>Add Rows</label>
            <button class="btn btn-danger" id = "addMore" >+ rows</button>
        </div>

    </div>

    <div id="divRepeat">
        <div class="row" id = "actvity_class" style="background-color: #00A6C7">
            <div class="col">
                <label>Group</label>
                <input type="text" id="group" name="activityGroup[]"   class="form-control" required >
                <div class="invalid-feedback">
                    Please provide a valid date.
                </div>
            </div>

            <div class="col">
                <label>Date</label>
                <input type="date" id="date1" name="activityDate[]"  min="2020-01-01" max="2020-04-31" class="form-control" required >
                <div class="invalid-feedback">
                    Please provide a valid date.
                </div>
            </div>
            <div class="col">
                <label>Start Time</label>
                <input type="time" id="start" name="activityStTime[]"  class="form-control" min="08:00" max="17:00" required>
                <div class="invalid-feedback">
                    Please provide a valid time.
                </div>
            </div>
            <div class="col">
                <label>End Time</label>
                <input type="time" id="endtm" name="activityEnTime[]"  class="form-control" min="08:00" max="17:00" required>
                <div class="invalid-feedback">
                    Please provide a valid time.
                </div>
            </div>
        </div>
    </div>


<script>
    /*
    $(document).on('click','#addMore',function(e) {
       // $("#actvity_class").append($("#actvity_class").html());
        var groupCount = document.getElementById("group-count").value;
        //alert(groupCount);
        for (i = 1; i < groupCount; i++) {
            //alert(i);
            //$("#divRepeat").append($("#actvity_class").html());
            $("#divRepeat").append($("#actvity_class").clone());
        }

    });
*/
</script>
<hr>
</div>
<div id = "nonGroupDetails">
                    <div class="form-group">
                        <label>Lecturer</label>
                        <?php loadAcademicDepartments(); ?>
                    </div>

                    <div class="form-group">
                        <label>Venue</label>
                        <?php loadLabs();?>
                    </div>
                    <input type="hidden" id="stTime" name="classStTime"  >
                    <input type="hidden" id="enTime" name="classEnTime" >
</div>
                    <div class="modal-footer">
                        <input type="hidden" value="1" name="type">
                        <input type="reset" class="btn btn-default"  value="Reset">
                        <button class="btn btn-default" data-dismiss="modal">CANCEL</button>
                        <button class="btn btn-primary" id="btn-add" onclick="checkTime();">SAVE</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
    <script>
        $("#createEventModal").on("show.bs.modal",function(){
            var str1 = document.getElementById("getBatch").value;
            $('#selectBatch option[value="' + str1 +'"]').prop('selected', true);
            $('#selectBatch').prop('disabled', true);
            var str2 = document.getElementById("getMod").value;
            $('#selectModule option[value="' + str2 +'"]').prop('selected', true);
            $('#selectModule').prop('disabled', true);
            $("#grpdetails").hide();
        });

        $("#createEventModal").on("hidden.bs.modal",function(){
            $('#user_form')[0].reset();
        });
    </script>
<!--Edit Modal-->
    <div id="editEventModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Edit Class on <div id="modalWhen" style="margin-top:5px;"></div></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div id="modalBody" class="modal-body">
                    <form class="needs-validation" novalidate id="update_form">
                        <div class="form-group">
                            <label>Activity</label>
                            <input type="hidden" id="activityType" name="mphase" class="form-control">
                            <?php loadActivity();?>
                        </div>

                        <div class="form-group">
                            <label>Topic</label>
                            <input name="classTopicVal" id="classTopicVal" placeholder="class topic" type="text" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid class topic.
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lecturer</label>
                            <?php loadAcademicDepartments(); ?>
                        </div>

                        <div class="form-group">
                            <label>Venue</label>
                            <?php loadLabs();?>
                        </div>
                        <input type="hidden" id="stTime" name="classStTime">
                        <input type="hidden" id="enTime" name="classEnTime">
                        <input type="hidden" id="classDescription" name="classDetails">
                        <input type="hidden" id="classID" name="classReserveID" placeholder="class id">
                        <div class="modal-footer">
                            <input type="hidden" value="2" name="type">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <button type="button" class="btn btn-info" id="update">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        $("#editEventModal").on("show.bs.modal",function(){
            var str1 = document.getElementById("classDescription").value;
           //activity-topic-staff/department/venue
            var str2 = str1.split('<br>');
            var classActivity = str2[0].trim();
            var  classTopic = str2[1];
            var classStaffDep = str2[2].trim();
            var classVenue =  str2[3].trim();
           $('[name=selectActivity]').find('option:contains("' + classActivity + '")').attr('selected', 'selected');
           //$('[name=topic]').find('option:contains("' + classActivity + '")').attr('selected', 'selected');
            $('[name=classTopicVal]').val(classTopic);
           $('[name=selectAcademicDep]').find('option:contains("' + classStaffDep + '")').attr('selected', 'selected');
           $('[name=selectLab]').find('option:contains("' + classVenue + '")').attr('selected', 'selected');
        });
        $("#editEventModal").on("hidden.bs.modal",function(){
            $('#update_form')[0].reset();
        });
    </script>
    <!--EDIT DELETE MODAL-->
    <div id="editDeleteEventModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Class on <div id="modalWhen" style="margin-top:5px;"></div></h4>
                </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_d" name="id" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <a href="#editEventModal" class="btn btn-primary" data-id="classID" data-toggle="modal" data-dismiss="modal">Edit</a>
                        <a href="#deleteClassModal" class="btn btn-danger" data-id="deleteID" data-toggle="modal" data-dismiss="modal">Delete</a>
                    </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal HTML -->
    <div id="deleteClassModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4>Delete Class <div id="delmodalWhen" style="margin-top:5px;"></div></h4>
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

    <!-- Confirm Timetable Modal HTML -->
    <div id="confirmTimetableModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4>Confirm Timetable <br> Batch <?php echo $batchhMod[0]. ' - ' . $batchhMod[2];  ?> Module</h4>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="id_c" name="id_c" class="form-control" value="<?php echo $batchhMod[0]. '-' . $batchhMod[1];  ?>">
                        <p>Tentative Timetable will be visible to the Faculty for comments and Sugestions </p>
                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Edit Modal ???????????????????????-->
    <div id="holidayClick" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Edit Class on <div id="modalWhen" style="margin-top:5px;"></div></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div id="modalBody" class="modal-body">

                </div>

            </div>
        </div>
    </div>
    <script src="assets/scripts/calander/timetable-calender.js" data-params="<?php echo $getVal; ?>"></script>
</html>
<?php  } ?>