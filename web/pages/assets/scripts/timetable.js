function checkTime() {
    var stTm = document.getElementById('stTime').value;
    var enTm = document.getElementById('enTime').value;

    if (stTm >= enTm) {
        alert('Check start time and end time');
        document.getElementById('start').focus;
            return false;
    }
    return true;
}
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
// Add Class
	$(document).on('click','#btn-add',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            
            // Check if Group Class checkbox is checked
            if($('#groupClass').is(':checked')) {
                // Redirect to Group Class page with necessary parameters
                var batch = $('#getBatch').val();
                var module = $('#getMod').val();
                var activity = $('#selectActivity').val();
                var topic = $('#topic').val();
                var startTime = $('#stTime').val();
                var endTime = $('#enTime').val();
                
                // Construct URL with parameters
                var redirectUrl = 'add_GroupClass.php?batch=' + batch + 
                                 '&module=' + module + 
                                 '&activity=' + activity + 
                                 '&topic=' + encodeURIComponent(topic) + 
                                 '&startTime=' + encodeURIComponent(startTime) + 
                                 '&endTime=' + encodeURIComponent(endTime);
                
                window.location.href = redirectUrl;
                return;
            }
            
            var data = $("#user_form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/timetable.php",
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#createEventModal').modal('hide');
                        alert('Data added successfully !');
                        location.reload();
                        calendar.fullCalendar('refetchEvents');
                    }
                    else if (dataResult.statusCode == 201) {
                        alert(dataResult);
                    }
                }
            });
        }else{
            $("#user_form")[0].reportValidity()
        }
	});
// Update
$(document).on('click','#update',function(e) {
    var valid = this.form.checkValidity();
    if(valid) {
        event.preventDefault();
        var data = $("#update_form").serialize();
        //alert(data);
        $.ajax({
            data: data,
            type: "post",
            url: "assets/scripts/backend/timetable.php",
            success: function (dataResult) {
               // alert(dataResult);
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    $('#editEventModal').modal('hide');
                    alert('Data updated successfully !');
                    location.reload();
                    calendar.fullCalendar('refetchEvents');
                }
                else if (dataResult.statusCode == 201) {
                    alert(dataResult);
                }
            }
        });
    }else{
        $("#update_form")[0].reportValidity();
    }
});
$(document).on("click", "#delete", function() {
  var passID2 =  $('#deleteClassModal #id_d').val();
    $.ajax({
        url: "assets/scripts/backend/timetable.php",
        type: "POST",
        cache: false,
        data:{
            type:5,
            id: passID2
        },
        success: function(){
            $('#deleteClassModal').modal('hide');
            location.reload();
        }
    });
});
$(document).on("click", "#confirm", function() {
    var passID2 =  $('#confirmTimetableModal #id_c').val();
    alert(passID2);
    $.ajax({
        url: "assets/scripts/backend/timetable.php",
        type: "POST",
        cache: false,
        data:{
            type:6,
            id: passID2
        },
        success: function(dataResults){
            alert(dataResults);
            $('#confirmTimetableModal').modal('hide');
            location.reload();
        }
    });
});
$(document).on("click", "#cancel", function() {
    var passID3 =  $('#cancelClassModal #id_c').val();
    $.ajax({
        url: "assets/scripts/backend/timetable.php",
        type: "POST",
        cache: false,
        data:{
            type:7,
            id: passID3
        },
        success: function(){
            $('#cancelClassModal').modal('hide');
            location.reload();
        }
    });
});
$(document).on("click", "#postpone", function() {
    var passID4 =  $('#postponeClassModal #id_p').val();
    $.ajax({
        url: "assets/scripts/backend/timetable.php",
        type: "POST",
        cache: false,
        data:{
            type:8,
            id: passID4
        },
        success: function(){
            $('#postponeClassModal').modal('hide');
            location.reload();
        }
    });
});
$(document).on("click", "#reschedule", function() {
    var passID5 =  $('#rescheduleEventModal #id_r').val();
    var passDt =  $('#rescheduleEventModal #date1').val();
    $.ajax({
        url: "assets/scripts/backend/timetable.php",
        type: "POST",
        cache: false,
        data:{
            type:9,
            id: passID5,
            cdt: passDt
        },
        success: function(dataResult){
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                alert('Reschedule successfully !');
                $('#rescheduleEventModal').modal('hide');
                location.reload();
                calendar.fullCalendar('refetchEvents');
            }
            else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }

        }
    });
});
$(document).on("click", "#rescheduleWeek", function() {
    var passID5 =  $('#rescheduleEventModal #id_r').val();
    var passDt =  $('#rescheduleEventModal #date1').val();
    $.ajax({
        url: "assets/scripts/backend/timetable.php",
        type: "POST",
        cache: false,
        data:{
            type:10,
            id: passID5,
            cdt: passDt
        },
        success: function(dataResult){
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                alert('Reschedule successfully !');
                $('#rescheduleEventModal').modal('hide');
                location.reload();
                calendar.fullCalendar('refetchEvents');
            }
            else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }

        }
    });
});

