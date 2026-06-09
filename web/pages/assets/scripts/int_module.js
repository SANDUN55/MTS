	<!--  Module Chair- Co Chair -->
	$(document).on('click','#btn-add',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#user_form").serialize();
            //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/int_module.php",
                success: function (dataResult) {
                   //alert(dataResult);
                    var dataResult = JSON.parse(dataResult);
                   // alert(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#addBatchModal').modal('hide');
                        alert('Data added successfully !');
                        location.reload();
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
    $(document).on('click','.update',function(e) {
        var id1 = $(this).attr("data-id1");
        var id2 = $(this).attr("data-id2");
        var mc = $(this).attr("data-mc");
        var ma = $(this).attr("data-ma");
        $('#id1_u').val(id1);
        $('#id2_u').val(id2);
        $('#mc_u').val(mc);
        $('#mp_u').val(ma);
    });
	<!-- Update Module Chair- Co Chair-->
	$(document).on('click','#update',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#update_form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/int_module.php",
                success: function (dataResult) {
                    //alert(dataResult);
                    var dataResult = JSON.parse(dataResult);
                    //alert(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#editModuleModal').modal('hide');
                        alert('Data updated successfully !');
                        location.reload();
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
<!-- Timetable Managers-->
    $(document).on('click','.updatett',function(e) {
        var id1 = $(this).attr("data-id1");
        var id2 = $(this).attr("data-bn");
        var mc = $(this).attr("data-mc");
        var dp = $(this).attr("data-dp");
        $('#id1_u').val(id1);
        $('#id2_u').val(id2);
        $('#mc_u').val(mc);
        $('#dp_u').val(dp);
    });
    $(document).on('click','#updatett',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#update_form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/int_module.php",
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#editModuleModal').modal('hide');
                        alert('Data updated successfully !');
                        location.reload();
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
    $(document).on("click", ".delete", function() {
        var id=$(this).attr("data-id");
        $('#id_d').val(id);

    });
    $(document).on("click", "#delete", function() {
        $.ajax({
            url: "assets/scripts/backend/int_module.php",
            type: "POST",
            cache: false,
            data:{
                type:4,
                id: $("#id_d").val()
            },
            success: function(dataResult){
                //alert(dataResult);
                $('#deleteTTmngModal').modal('hide');
                location.reload();
            }
        });
    });
    $(document).on('click','.updateDate',function(e) {
        var id1 = $(this).attr("data-id1");
        var bn = $(this).attr("data-bn");
        var mc = $(this).attr("data-mc");
        var md = $(this).attr("data-md");
        $('#id_u').val(id1);
        $('#bno_u').val(bn);
        $('#bmd_u').val(mc);
        $('#date1').val(md);
    });
    $(document).on('click','#updateDt',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#updateDT_form").serialize();
            //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/int_module.php",
                success: function (dataResult) {
                   // alert(dataResult);
                    var dataResult = JSON.parse(dataResult);
                   //alert(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#editModuleModal').modal('hide');
                        alert('Data updated successfully !');
                        location.reload();
                    }
                    else if (dataResult.statusCode == 201) {
                        alert(dataResult);
                    }
                }
            });
        }else{
            $("#updateDT_form")[0].reportValidity();
        }
    });
    //CONFIRM MODULE
    $(document).on('click','.summary',function(e) {
        var id = $(this).attr("data-id");
        var md = $(this).attr("data-id2");
        //$('#displaySummary #modalWhen').text(id);
       $('#id_u').val(id);
       $('#id_m').val(md);
    });
    $(document).on('click','.confirm',function(e) {
       var val1 = $(this).attr("data-id");
       var val2 = $(this).attr("data-id2");
       var val3 = $(this).attr("data-id3");
       var binfoo = "Batch " +  val1 + ' - ' + val3;
       $('#bno').val(val1);
       $('#mco').val(val2);
       $('#binfo').html(binfoo);
        //binfo
    });
    $(document).on('click','#confimMod',function(e) {
        //event.preventDefault();
        var data = $("#confirm_form").serialize();
            //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/int_module.php",
                success: function (dataResult)  {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#confirmBatchModule').modal('hide');
                        alert('Data updated successfully !');
                        location.reload();
                    }
                    else if (dataResult.statusCode == 201) {
                        alert(dataResult);
                    }
                }
            });
    });
    // END MODULE
    $(document).on("click", ".endModule", function() {
        var id=$(this).attr("data-id2");
        $('#id_d').val(id);
    });
    $(document).on("click", "#endModule", function() {
        $.ajax({
            url: "assets/scripts/backend/int_module.php",
            type: "POST",
            cache: false,
            data:{
                type:8,
                id: $("#id_d").val()
            },
            success: function(dataResult){
                $('#endModuleModal').modal('hide');
                // $("#"+dataResult).remove();
                location.reload();
            }
        });
    });
    // PUBLISH TENTATIVE TIMETABLE MODULE
    $(document).on("click", ".pubTentative", function() {
        var id3 = $(this).attr("data-id3");
        $('#id_dtn').val(id3);
    });
    $(document).on("click", "#publishTentv", function() {
        $.ajax({
            url: "assets/scripts/backend/int_module.php",
            type: "POST",
            cache: false,
            data:{
                type:9,
                id: $("#id_dtn").val()
            },
            success: function(dataResult){
                alert('Data updated successfully !');
                $('#pubTentativeTimetable').modal('hide');
                //$("#"+dataResult).remove();
                location.reload();
            }
        });
    });
    //CONFIRM MODULE RESERVATIONS
  /*  $(document).on('click','.confirmRes',function(e) {
        var val1 = $(this).attr("data-rid");
        var val2 = $(this).attr("data-rid2");
        var val3 = $(this).attr("data-rid3");
        var binfoo = "Batch " +  val1 + ' - ' + val3;
        $('#rbno').val(val1);
        $('#rmco').val(val2);
        $('#rbinfo').html(binfoo);
        //binfo
    });*/