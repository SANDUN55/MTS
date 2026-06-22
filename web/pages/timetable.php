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
    <link rel="stylesheet" href="assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <link rel="stylesheet" href="assets/scripts/calander/bootstrap3.4.1.min.css">
    <script src="assets/scripts/calander/bootstrap3.4.1.min.js"></script>
    <script src="assets/scripts/timetable.js"></script>
    <style>
        .answer { display:none }
    </style>
<?php  include 'assets/scripts/backend/select_val.php';?>
    <div class="app-main">
    <?php include 'navbar-left.php'; ?>
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="row">
                   <!-- <div class="col-2" style="display: block;">-->
                        <?php
                        $batchhMod = array(); $getVal='';
                        if($_GET['id']) {
                            $getVal = $_GET['id'];
                            $batchhMod = explode('-', $getVal);
                        }  ?>
                        <?php
                        $batch = $batchhMod[0];
                        $mod = $batchhMod[1];
                        $ttStatus = '';
                        database_conectivity();
                        $ttStatusGet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ttprogress  FROM batchmodule  WHERE `b_no` = $batch AND `m_code` = '$mod'"));

                        if($ttStatusGet['ttprogress']) {
                            $ttStatus = $ttStatusGet['ttprogress'];
                        }
                        //echo $ttStatus;
                        ?>
<!--                        <?php /*if($ttStatus == 2) { */?>
                        <div id='external-events'>
                            <p>
                                <strong>Load Cancel/Postpone Classes </strong>
                                <button id="showme">Show</button>
                                <button id="hideme">Hide</button>
                            </p>
                            <div class="obj1" ><div class="obj2" style="display: none;">
                                    <?php
/*                                    database_conectivity();
                                    $batch = $batchhMod[0];
                                    $mod = $batchhMod[1];
                                    $ttStatus = '';
                                    $result = mysqli_query($conn,"SELECT class_topic, class_group, a_name, CONCAT(class_id, '-', class_topic_id) as tids 
                                          FROM classschedules s
                                          JOIN classtopics t ON t.topic_id = s.class_topic_id
                                          LEFT JOIN activity  ON activity = a_id
                                          WHERE b_no = $batch AND m_code = '$mod'
                                          AND class_status IN (0, 3) ");
                                    while($row = mysqli_fetch_array($result)) { */?>
                                        <div class='fc-event'  data-duration='03:00' data-event="<?php /*echo $row["tids"]; */?>"><?php /*echo $row["class_topic"] . ' (' . $row["a_name"] . ') '; */?></div>
                                    <?php /*     } */?>
                                </div></div>
                            <script> 
                                $("#hideme").click(function () {
                                    $(".obj2").hide('slide', {
                                        direction: 'left'
                                    }, 800);
                                });
                                $("#showme").click(function () {

                                    $(".obj2").attr('display', 'block');
                                    $(".obj2").show('slide', '', 800);
                                });
                            </script>
                        </div>
                        --><?php /*} */?>
    <!--                    <?php /*if($ttStatus == 1) { */?>
                        <div id='external-events2'>
                            <p>
                                <strong>Import from Previous Batch  </strong>
                                <button id="showme2">Show</button>
                                <button id="hideme2">Hide</button>
                            </p>
                            <div class="obj3" ><div class="obj4" style="display: none;">
                                    <?php
/*                                    database_conectivity();
                                    $batch = $batchhMod[0]; $prev_batch = $batch - 1 ;
                                    $mod = $batchhMod[1];
                                    $ttStatus = '';
                                    $result = mysqli_query($conn,"SELECT class_topic, class_group, a_name, CONCAT(class_id, '-', class_topic_id) as tids 
                                          FROM classschedules s
                                          JOIN classtopics t ON t.topic_id = s.class_topic_id
                                          LEFT JOIN activity  ON activity = a_id
                                          WHERE b_no = $prev_batch AND m_code = '$mod'
                                          AND class_status = 1");
                                    while($row = mysqli_fetch_array($result)) { */?>
                                        <div class='fc-event'  data-duration='03:00' data-event="<?php /*echo $row["tids"]; */?>"><?php /*echo $row["class_topic"] . ' (' . $row["a_name"] . ') '; */?></div>
                                    <?php /*     } */?>
                                </div></div>
                            <script>
                                $("#hideme2").click(function () {
                                    $(".obj4").hide('slide', {
                                        direction: 'left'
                                    }, 800);
                                });
                                $("#showme2").click(function () {

                                    $(".obj4").attr('display', 'block');
                                    $(".obj4").show('slide', '', 800);
                                });
                            </script>
                        </div>
                        --><?php /*} */?>
                   <!-- </div>-->
                    <div class="col-12">
                         <div class="main-card mb-3 card">
                             <div class="card-body">
                                 <nav class="" aria-label="breadcrumb">

                                     <ol class="breadcrumb">
                                         <li class="breadcrumb-item"><a href="registry_home.php">Calender</a></li>
                                         <li class="breadcrumb-item"><a href="registry_home.php">Timetable</a></li>
                                         <li class="active breadcrumb-item" aria-current="page"><h4 align="center"> Batch <?php  echo $batchhMod[0]. ' - ' . $batchhMod[2]. ' Module' ; ?>  </h4></li>
                                     </ol>
                                 </nav>
                                <div align="center" style="color: mediumvioletred"><p> <h3>
                                    <?php
                                    if($ttStatus==1)
                                        echo "Draft Timetable";
                                    elseif($ttStatus==2)
                                        echo "Tentative Timetable";
                                    elseif($ttStatus==3)
                                        echo "Confirmed Timetable";
                                    ?></p></h3>
                                </div>
                             </div>
                             <div class="card-body">
                                 <div id="calendar"></div>
                                    color scheme :
                                 <span style="background-color: beige;">&nbsp; active</span>
                                 <span style="background-color: #ff0066;">&nbsp; cancel</span>
                                 <span style="background-color: #0099ff;">&nbsp; postpone</span>
                             <div>
                         </div>
                     </div>
                 </div>

            </div><div class="row">
                        <div class="col">
            <?php include 'footer.php'; ?>
                        </div>
                    </div>
        </div>
    </div></div></div>

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
                        <input type="checkbox" id="groupClass" name="groupClass" value="1">
                        <label>Group Class</label>
                    </div>
  <script>
$('#groupClass').on('change', function() {
    if($(this).is(':checked')) {
        // Redirect to Group Class page with necessary parameters
        var batch = $('#getBatch').val();
        var module = $('#getMod').val();
        var activity = $('#selectActivity').val();
        var topic = $('#topic').val();
        var startTime = $('#stTime').val();
        var endTime = $('#enTime').val();
        
        // Check if activity is selected
        if(!activity) {
            alert('Please select an activity first!');
            $(this).prop('checked', false);
            return;
        }
        
        // Construct URL with parameters
        var redirectUrl = 'add_GroupClass.php?batch=' + batch + 
                         '&module=' + module + 
                         '&activity=' + activity + 
                         '&topic=' + encodeURIComponent(topic) + 
                         '&startTime=' + encodeURIComponent(startTime) + 
                         '&endTime=' + encodeURIComponent(endTime);
        
        window.location.href = redirectUrl;
    }
});
</script>
                    <div class="form-group">
                        <label>Topic</label>
                        <input name="classTopic" id="topic" placeholder="class topic" type="text" class="form-control" required>
                        <div class="invalid-feedback">
                            Please provide a valid class topic.
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Lecturer </label> &nbsp;<label> (NOTE :unavailable staff members are disable in the list)</label>

                        <?php loadAcademicDepartments(1); ?>

                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="onlinec" name="onlinec" value="0" >
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
                    <div class="form-group" id="divrem" name="divrem" style="display: none;" >
                        <label>ZOOM Link </label>
                        <input type="text" id="remark" name="remark"  class="form-control" >
                    </div>
                    <div class="form-group" id="diven" name="diven" style="display: block;">
                        <label>Venue<div style="color: #AF2018"><em>consider the student count with lab size selecting a venue</em></div></label>
                        <?php loadLabs();?>
                    </div>
                    <input type="hidden" id="stTime" name="classStTime"  >
                    <input type="hidden" id="enTime" name="classEnTime" >

                    <div class="modal-footer">
                        <input type="hidden" value="1" name="type" id="type">
                        <input type="reset" class="btn btn-default"  value="RESET">
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
            $('#selectModule option[value="' + str2.trim() +'"]').prop('selected', true);
            $('#selectModule').prop('disabled', true);
            var startTm = document.getElementById("stTime").value;
            var enTm = document.getElementById("enTime").value;
            var val = startTm + ',' + enTm;
           // alert(val);
            $.ajax({
                type: "POST",
                url: "assets/scripts/backend/get_val.php",
                data:'fid='+ 6 + '&fval=' + val,
                success: function(data){
                   var staffIds = data.split(',');
                   var len = (staffIds.length) - 1;
                    var i;
                    for (i = 0; i < len; i++) {
                        var staffId = staffIds[i];
                        $('#selectAcademicDep option[value="' + staffId +'"]').prop('disabled', true);
                    }
                }
            });
        });
        $("#createEventModal").on("hidden.bs.modal",function(){
            $('#user_form')[0].reset();
        });
    </script>
    <script>
        $('#selectLab').on('change', function() {
           // alert('ok');
            var val = document.getElementById("selectLab").value;
            var sdt1 = document.getElementById("stTime").value;
            var sdt2 = sdt1.split('T');
            var sdt = sdt2[0];
            var sst = sdt2[1];
            var set1 = document.getElementById("enTime").value;
            var sdt2 = set1.split('T');
            var set = sdt2[1];
            var str = val + ',' + sdt + ',' + sst + ',' + set;
            // alert(str);
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
                        <input type="hidden" id="startTime" name="classStTime">
                        <input type="hidden" id="endTime" name="classEnTime">
                        <input type="hidden" id="classDescription" name="classDetails">
                        <input type="hidden" id="classTitle" name="classTitle">
                        <input type="hidden" id="classID" name="classReserveID" placeholder="class id">
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
                            <label>Group</label>
                            <input name="classGroupVal" id="classGroupVal" placeholder="groups/ groups" type="text" class="form-control" >
                            <div class="invalid-feedback">
                                Please provide a valid class topic.
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lecturer</label>
                            <?php
                            // Initialize preselected lecturers array to avoid undefined variable error
                            $preselectedLecturers = array();
                            loadAcademicDepartments(2); // for lecturer field 2
 ?>
                        </div>

                        <div class="form-group" id="editdiv">
                            <label>Venue<div style="color: #AF2018"><em>consider the student count with lab size selecting a venue</em></div></label>
                            <?php loadLabs();?>
                        </div>
                        <script>
                            $('#editdiv #selectLab').on('change', function() {
                                //alert('okaaa');
                                var val = this.value;
                                var sdt1 = document.getElementById("startTime").value;
                                var sdt2 = sdt1.split(' ');
                                var sdt = sdt2[0];
                                var sst = sdt2[1];
                                var set1 = document.getElementById("endTime").value;
                                var sdt2 = set1.split(' ');
                                var set = sdt2[1];
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
                                            $('#update').prop('disabled', true);
                                        }else if(data=='T'){
                                            $('#update').prop('disabled', false);
                                        }
                                    }
                                });
                            });
                        </script>
                        <div class="form-group">
                            <input type="checkbox" id="onlinec" name="onlinec" value="0" >
                            <label>Online Class </label> <input type="text" id="remark" name="remark" placeholder="ZOOM Link" class="form-control" >
                        </div>
                      <!--  <div class="form-group" id="divrem" name="divrem"  >
                            <label>ZOOM Link </label>
                            <input type="text" id="remark" name="remark"  class="form-control" >
                        </div>-->
                        <div class="modal-footer">
                            <input type="hidden" value="2" name="type" id="type">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <button type="button" class="btn btn-info" id="update">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
   
<!-- <script>
    $("#editEventModal").on("show.bs.modal",function(){
        var str1 = document.getElementById("classTitle").value;
        var str3 = document.getElementById("classDescription").value;
        var startTm = document.getElementById("startTime").value;
        var enTm = document.getElementById("endTime").value;
        var str2 = str1.split(':');
        var str4 = str3.split(',');
        var classActivity = str4[0];
        var zooml = str4[1];
        var  classTopic1 = str2[0];
        var  classTopic2 = classTopic1.replace(/"/g, " ");
        var  classTopic = classTopic2.trim();
        var  classGroup = str2[1].trim();
        // var classStaffDep = str2[2].trim();
        var lecturerString = str2[2].trim();
        var classStaffDep = lecturerString.split(',').map(name => name.trim());
        // var classStaffDep = str2[2].trim().split('-'); // array of lecturer values
        var classVenue =  str2[3].trim();
        $('[name=selectActivity]').find('option:contains("' + classActivity + '")').attr('selected', 'selected');
        $('[name=classTopicVal]').val(classTopic);
         $('[name=classGroupVal]').val(classGroup);
        // $('[name=selectedLecturers2]').val(classStaffDep);
        // opt= selectOptionByText("selectedLecturers2", classStaffDep);
        // $('[name=selectedLecturers2]').find('option:contains("' + classStaffDep + '")').attr('selected', 'selected');
            var selectBox = document.getElementById("selectAcademicDep2");
            for (let i = 0; i < selectBox.options.length; i++) {
                let option = selectBox.options[i];
                if (classStaffDep.includes(option.text.trim())) {
                    option.selected = true;
                }
            }
// After selecting all, call this to render tags and hidden fields
addSelectedLecturer(2);

        $('[id=selectLab]').find('option:contains("' + classVenue + '")').attr('selected', 'selected');
        $('[name=remark]').val(zooml);
        var val = startTm + ',' + enTm;
        $.ajax({
            type: "POST",
            url: "assets/scripts/backend/get_val.php",
            data:'fid='+ 6 + '&fval=' + val,
            success: function(data){
                var staffIds = data.split(',');
                var len = (staffIds.length) - 1;
                for (var i = 0; i < len; i++) {
                    var staffId = staffIds[i];
                    $('#selectAcademicDep option[value="' + staffId +'"]').prop('disabled', true);
                }
            }
        });
    });

    $("#editEventModal").on("hidden.bs.modal",function(){
        $('#update_form')[0].reset();
    });
</script> -->

<script>
// ==================== EDIT MODAL ====================
$("#editEventModal").on("show.bs.modal", function() {
    var title = $('#editEventModal #classTitle').val() || '';
    var desc  = $('#editEventModal #classDescription').val() || '';
    var startTime = $('#editEventModal #startTime').val();
    var endTime   = $('#editEventModal #endTime').val();

    console.log("Title received:", title);
    console.log("Desc received:", desc);

    if (!title) return;

    // Clean title
    var cleanTitle = title.replace(/📍/g, '').replace(/\n+/g, ' | ').trim();
    var parts = cleanTitle.split(/\s*\|\s*/);

    var classActivity = (parts[0] || '').trim();
    var classTopic    = (parts[1] || '').trim();
    var lecturersStr  = (parts[2] || '').trim();
    var classVenue    = (parts[3] || '').trim();

    // === RESET ALL FIELDS FIRST ===
    $('#update_form')[0].reset();                    // Reset form
    $('select[name="selectActivity"]').val('');      // Reset Activity
    $('#classTopicVal').val('');
    $('#classGroupVal').val('');
    $('#remark').val('');

    // Reset Lecturer Select + Tags
    var selectBox = document.getElementById("selectAcademicDep2");
    if (selectBox) {
        for (let option of selectBox.options) {
            option.selected = false;
        }
    }
    // Clear any custom tags if addSelectedLecturer creates them
    if (typeof clearSelectedLecturers === "function") {
        clearSelectedLecturers(2);
    }

    // === POPULATE NEW DATA ===

    // Activity
    $('select[name="selectActivity"]').find('option').each(function(){
        if ($(this).text().trim() === classActivity || $(this).val() === classActivity) {
            $(this).prop('selected', true);
        }
    });

    // Topic
    $('#classTopicVal').val(classTopic);
    $('#classGroupVal').val('');

    // Venue
    if (classVenue) {
        var venueClean = classVenue.replace(/\(\d+ PCs?\)/i, '').trim();
        $('#editdiv select option').each(function(){
            var optText = $(this).text().trim();
            if (optText.includes(venueClean) || optText.includes(classVenue)) {
                $(this).prop('selected', true);
            }
        });
    }

    // Lecturers
    var lecturers = lecturersStr ? lecturersStr.split(',').map(l => l.trim()) : [];
    if (selectBox) {
        for (let option of selectBox.options) {
            option.selected = lecturers.some(l => 
                option.text.trim().includes(l) || l.includes(option.text.trim())
            );
        }
    }

    // Call your custom lecturer UI function
    if (typeof addSelectedLecturer === "function") {
        addSelectedLecturer(2);
    }

    // Disable unavailable staff
    if (startTime && endTime) {
        $.ajax({
            type: "POST",
            url: "assets/scripts/backend/get_val.php",
            data: 'fid=6&fval=' + startTime + ',' + endTime,
            success: function(data){
                var staffIds = data.split(',');
                for (var i = 0; i < staffIds.length; i++) {
                    var sid = staffIds[i].trim();
                    if (sid) {
                        $('#selectAcademicDep2 option[value="' + sid + '"]').prop('disabled', true);
                    }
                }
            }
        });
    }
});

$("#editEventModal").on("hidden.bs.modal", function(){
    $('#update_form')[0].reset();
});
</script>

    <!--EDIT/ DELETE MODAL-->
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
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
                        <?php
                        database_conectivity();
                        $batch = $batchhMod[0];
                        $mod = $batchhMod[1];
                        $ttStatus = '';
                        $result = mysqli_query($conn,"SELECT ttprogress FROM batchmodule WHERE b_no = $batch AND m_code = '$mod'");
                        if($row = mysqli_fetch_array($result)) {
                            $ttStatus = $row["ttprogress"];
                        }
                        if($ttStatus == 1 || $ttStatus == 2) { ?>
                            <a href="#editEventModal" class="btn btn-primary" data-id="classID" data-toggle="modal" data-dismiss="modal"> Edit </a>
                            <!-- <a href="" class="btn btn-primary" data-id="classID" data-toggle="modal" data-dismiss="modal"> Edit </a> -->
                            <a href="#rescheduleEventModal" class="btn btn-alternate" data-id="resID" data-toggle="modal" data-dismiss="modal"> Reschedule </a>
                            <a href="#cancelClassModal" class="btn btn-danger" data-id="cancelID" data-toggle="modal" data-dismiss="modal">Cancel</a>
                            <a href="#deleteClassModal" class="btn btn-danger" data-id="deleteID" data-toggle="modal" data-dismiss="modal">Delete</a>
                            
                            <?php
                        }
                        
                        if($ttStatus == 3) { ?>
                            <a href="#editEventModal" class="btn btn-primary" data-id="classID" data-toggle="modal" data-dismiss="modal"> Edit </a>
                            <!-- <a href="" class="btn btn-primary" data-id="classID" data-toggle="modal" data-dismiss="modal"> Edit </a> -->
                            <a href="#cancelClassModal" class="btn btn-danger" data-id="cancelID" data-toggle="modal" data-dismiss="modal">Cancel</a>
                            <a href="#postponeClassModal" class="btn btn-alternate" data-id="postponeID" data-toggle="modal" data-dismiss="modal">Postpone</a>
                            <a href="#deleteClassModal" class="btn btn-danger" data-id="deleteID" data-toggle="modal" data-dismiss="modal">Delete</a>
                        
                            <?php } ?>
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

    <!-- Cancel Modal HTML -->
    <div id="cancelClassModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4>Cancel Class <div id="cnclmodalWhen" style="margin-top:5px;"></div></h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_c" name="id" class="form-control">
                        <p>Are you sure you want to Cancel this class?</p>
                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
                        <button type="button" class="btn btn-danger" id="cancel">Cancel</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Postpone Modal HTML -->
    <div id="postponeClassModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4>Postpone Class <div id="postpndmodalWhen" style="margin-top:5px;"></div></h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_p" name="id" class="form-control">
                        <p>Are you sure you want to postpone this class?</p>
                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
                        <button type="button" class="btn btn-alternate" id="postpone">Postpone</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
   <!-- Reschedule Day Class-->
   <div id="rescheduleEventModal" class="modal fade">
       <div class="modal-dialog">
           <div class="modal-content">
               <form>
                   <div class="modal-header">
                       <h4>Reschedule <b>All Classes</b> on <div id="resmodalWhen" style="margin-top:5px;"></div>
                           the date to  <div class="position-relative form-group"> <br>

                       </h4>
                   </div>
                   <div class="modal-body">
                       <input type="hidden" id="id_r" name="id" class="form-control">
                       <input type="date" id="date1" name="classDate"   class="form-control" required >
                       <div class="invalid-feedback">
                           Please provide a valid date.
                       </div>
                       <p class="text-danger"><small>All classes will be Reschedule in to the selected new date.</small></p>
                   </div>
                   <div class="modal-footer">
                       <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
                       <button type="button" class="btn btn-alternate" id="reschedule">Reschedule Day</button>
                      <!-- <button type="button" class="btn btn-alternate" id="rescheduleWeek">Reschedule Week</button>-->
                   </div>
               </form>
           </div>
       </div>
   </div>
   <!-- Confirm Timetable Modal HTML
    <div id="confirmTimetableModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4>Confirm Timetable <br> Batch <?php //echo $batchhMod[0]. ' - ' . $batchhMod[2];  ?> Module</h4>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="id_c" name="id_c" class="form-control" value="<?php //echo $batchhMod[0]. '-' . $batchhMod[1];  ?>">
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
    -->
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
