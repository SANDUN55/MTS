	<!-- Add batch -->
	$(document).on('click','#btn-add',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#user_form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/module.php",
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
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
    var id=$(this).attr("data-id");
        var mc=$(this).attr("data-mc");
        var mn=$(this).attr("data-mn");
        var mp=$(this).attr("data-mp");
        var ms=$(this).attr("data-ms");
        $('#id_u').val(id);
        $('#mc_u').val(mc);
        $('#mn_u').val(mn);
        $('#mp_u').val(mp);
        $('#ms_u').val(ms);
    });

	<!-- Update -->
	$(document).on('click','#update',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#update_form").serialize();
            //alert(data);
            //id=PDFM1&mcode=PDFM1&mname=Professional+Development+And+Family+Medicine&mphase=1&selectPhase=1&mstrand=6&selectStrand=6&type=2
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/module.php",
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
			url: "assets/scripts/backend/module.php",
			type: "POST",
			cache: false,
			data:{
				type:3,
				id: $("#id_d").val()
			},
			success: function(dataResult){
					$('#deleteBatchModal').modal('hide');
					$("#"+dataResult).remove();
                	location.reload();
            }
		});
	});
    $(document).on("click", ".disable", function() {
        var id=$(this).attr("data-id");
        $('#id_d').val(id);

    });
    $(document).on("click", "#disable", function() {
        $.ajax({
            url: "assets/scripts/backend/module.php",
            type: "POST",
            cache: false,
            data:{
                type:4,
                id: $("#id_d").val()
            },
            success: function(dataResult){
                $('#disableModuleModal').modal('hide');
               // $("#"+dataResult).remove();
                location.reload();
            }
        });
    });
