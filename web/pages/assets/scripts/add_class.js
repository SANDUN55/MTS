/*function checkTime() {
    alert('ok tt';)
    var stTm = document.getElementById('activityStart').value;
    var enTm = document.getElementById('activityEndtm').value;
    if (stTm >= enTm) {
        alert('Check start time and end time');
        return false;
    }

}
const picker = document.getElementById('date1');
picker.addEventListener('input', function(e){
    // alert(this.value);
    var day = new Date(this.value).getUTCDay();
    if([6,0].includes(day)){
        e.preventDefault();
        this.value = '';
        alert('Weekends not allowed');
    }
});*/

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
//ADD INDIVIDUAL /GROUP CLASS
	$(document).on('click','#btn-add',function(e) {
        e.preventDefault();
        
        // Check start and end times
        var stTm = document.getElementById('activityStart') ? document.getElementById('activityStart').value : '';
        var enTm = document.getElementById('activityEndtm') ? document.getElementById('activityEndtm').value : '';
        if (stTm && enTm && stTm >= enTm) {
            alert('Check start time and end time');
            return false;
        }

        var valid = this.form.checkValidity();
        if(valid) {
            var data = $("#user_form").serialize();
            console.log('Sending data:', data); // Debug log
            
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/add_class.php",
                dataType: "json",
                timeout: 10000,
               success: function (dataResult) {
    console.log('Response:', dataResult);
    
    if (dataResult.statusCode == 200) {
        alert('Data added successfully!');
        
        // Refresh iframe if exists (timetable page)
        var iframe = parent.$('#myIframe') || $('#myIframe');
        if (iframe.length) {
            var currentSrc = iframe.attr('src');
            iframe.attr('src', currentSrc + (currentSrc.indexOf('?') > -1 ? '&' : '?') + 't=' + new Date().getTime());
        } else {
            // If not in iframe, reload current page
            setTimeout(function() {
                location.reload();
            }, 800);
        }
        
        // Optional: Reset form
        $("#user_form")[0].reset();
    } 
    else if (dataResult.statusCode == 400) {
        alert('Error: ' + (dataResult.message || 'Failed to add class'));
    }
},
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown); // Debug log
                    console.error('Response Text:', jqXHR.responseText); // Log actual response
                    console.error('HTTP Status Code:', jqXHR.status); // Log HTTP status
                    
                    // Try to parse JSON response even in error
                    try {
                        var responseData = JSON.parse(jqXHR.responseText);
                        if (responseData.statusCode === 200) {
                            // Data was actually saved successfully
                            alert('Data added successfully!');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                            return;
                        } else if (responseData.message) {
                            alert('Error: ' + responseData.message);
                            return;
                        }
                    } catch(e) {
                        // Not valid JSON
                    }
                    
                    alert('Error: ' + (jqXHR.responseText || textStatus || errorThrown));
                }
            });
        }else{
            $("#user_form")[0].reportValidity();
            return false;
        }
	});
//CLASS CHANGE REQUEST APPROVE/REJECT
$(document).on('click','.aprRq',function(e) {
    var cid = $(this).attr("data-idc");
    var tid = $(this).attr("data-idt");
    var cst = $(this).attr("data-cst");
    var cen = $(this).attr("data-cen");
    var rid = $(this).attr("data-rid");
    $('#cid_u').val(cid);
    $('#tid_u').val(tid);
    $('#cst_u').val(cst);
    $('#cen_u').val(cen);
    $('#rid_u').val(rid);
});
$(document).on("click", "#aprClsReq", function() {
    var data = $("#frmReqData").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "assets/scripts/backend/add_class.php",
        dataType: "json",
        timeout: 10000,
        success: function (dataResult) {
            if (dataResult.statusCode == 200) {
                $('#approveReq').modal('hide');
                alert('Data updated successfully!');
                location.reload();
            }
            else if (dataResult.statusCode == 201) {
                alert('Error: ' + (dataResult.message || 'Failed to update data'));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
            alert('Error: ' + textStatus + ' - ' + errorThrown);
        }
    });
});
$(document).on('click','.reject',function(e) {
    var rid1 = $(this).attr("data-rd");
    $('#rid1').val(rid1);
});
$(document).on("click", "#rejClsReq", function() {
    var data = $("#frmRejData").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "assets/scripts/backend/add_class.php",
        dataType: "json",
        timeout: 10000,
        success: function (dataResult) {
            if (dataResult.statusCode == 200) {
                $('#rejectReq').modal('hide');
                alert('Data updated successfully!');
                location.reload();
            }
            else if (dataResult.statusCode == 201) {
                alert('Error: ' + (dataResult.message || 'Failed to update data'));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
            alert('Error: ' + textStatus + ' - ' + errorThrown);
        }
    });
});
//RESCHEDULE CLASS
 $(document).on('click','#btn-res',function(e) {
     e.preventDefault();
     var valid = this.form.checkValidity();
     if(valid) {
         var data = $("#user_form").serialize();
         $.ajax({
             data: data,
             type: "post",
             url: "assets/scripts/backend/add_class.php",
             dataType: "json",
             timeout: 10000,
             success: function (dataResult) {
                 if (dataResult.statusCode == 200) {
                     alert('Data updated successfully!');
                     location.reload();
                 }
                 else if (dataResult.statusCode == 201) {
                     alert('Error: ' + (dataResult.message || 'Failed to update data'));
                 }
             },
             error: function (jqXHR, textStatus, errorThrown) {
                 console.error('AJAX Error:', textStatus, errorThrown);
                 alert('Error: ' + textStatus + ' - ' + errorThrown);
             }
         });
     }else{
         $("#user_form")[0].reportValidity();
         return false;
     }
 });

 $(document).on('click','#btn-resDay',function(e) {
     e.preventDefault();
     var valid = this.form.checkValidity();
     if(valid) {
         var data = $("#user_form").serialize();
         $.ajax({
             data: { data: data, type:7 },
             type: "post",
             url: "assets/scripts/backend/add_class.php",
             dataType: "json",
             timeout: 10000,
             success: function (dataResult) {
                 if (dataResult.statusCode == 200) {
                     alert('Data updated successfully!');
                     location.reload();
                 }
                 else if (dataResult.statusCode == 201) {
                     alert('Error: ' + (dataResult.message || 'Failed to update data'));
                 }
             },
             error: function (jqXHR, textStatus, errorThrown) {
                 console.error('AJAX Error:', textStatus, errorThrown);
                 alert('Error: ' + textStatus + ' - ' + errorThrown);
             }
         });
     }else{
         $("#user_form")[0].reportValidity();
         return false;
     }
 });
 //---IMPORT PREVIOUS BATCH CLASSES
 $(document).on('click','#btn-impc',function(e) {
     e.preventDefault();
     var valid = this.form.checkValidity();
     if(valid) {
         var data = $("#user_form").serialize();
         $.ajax({
             data: data,
             type: "post",
             url: "assets/scripts/backend/add_class.php",
             dataType: "json",
             timeout: 10000,
             success: function (dataResult) {
                 if (dataResult.statusCode == 200) {
                     $('#addBatchModal').modal('hide');
                     alert('Data added successfully!');
                     location.reload();
                 }
                 else if (dataResult.statusCode == 201) {
                     alert('Error: ' + (dataResult.message || 'Failed to add data'));
                 }
             },
             error: function (jqXHR, textStatus, errorThrown) {
                 console.error('AJAX Error:', textStatus, errorThrown);
                 alert('Error: ' + textStatus + ' - ' + errorThrown);
             }
         });
     }else{
         $("#user_form")[0].reportValidity();
         return false;
     }
 });
  //----ADD CLINICAL ROTATIONS
$(document).on('click','#btn-add-clinical',function(e) {
    e.preventDefault();
    var valid = this.form.checkValidity();
    if(valid) {
        var data = $("#user_form").serialize();
        $.ajax({
            data: data,
            type: "post",
            url: "assets/scripts/backend/add_class.php",
            dataType: "json",
            timeout: 10000,
            success: function (dataResult) {
                if (dataResult.statusCode == 200) {
                    alert('Data added successfully!');
                    location.reload();
                }
                else if (dataResult.statusCode == 201) {
                    alert('Error: ' + (dataResult.message || 'Failed to add data'));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                alert('Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    }else{
        $("#user_form")[0].reportValidity();
        return false;
    }
});