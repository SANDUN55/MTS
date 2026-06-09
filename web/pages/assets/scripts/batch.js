	<!-- Add batch -->
	$(document).on('click','#btn-add',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#user_form").serialize();
            //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/batch.php",
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
        }
        else{
            $("#user_form")[0].reportValidity();
        }
	});
    $(document).on('click','.update',function(e) {
    var id=$(this).attr("data-id");
        var bno=$(this).attr("data-bno");
        var byr=$(this).attr("data-byr");
        var code=$(this).attr("data-code");
        var bst=$(this).attr("data-bst");
        $('#id_u').val(id);
        $('#bno_u').val(bno);
        $('#byr_u').val(byr);
        $('#code_u').val(code);
        $('#bst_u').val(bst);
    });

	<!-- Update -->
	$(document).on('click','#update',function(e) {
		var data = $("#update_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "assets/scripts/backend/batch.php",
			success: function(dataResult){
                    //alert(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#editBatchModal').modal('hide');
						alert('Data updated successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert(dataResult);
					}
			}
		});
	});
	$(document).on("click", ".delete", function() { 
		var id=$(this).attr("data-id");
		$('#id_d').val(id);
		
	});
	$(document).on("click", "#delete", function() { 
		$.ajax({
			url: "assets/scripts/backend/batch.php",
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
            url: "assets/scripts/backend/batch.php",
            type: "POST",
            cache: false,
            data:{
                type:4,
                id: $("#id_d").val()
            },
            success: function(dataResult){
                $('#disableBatchModal').modal('hide');
               // $("#"+dataResult).remove();
                location.reload();
            }
        });
    });
    $(document).on("click", ".upgrade", function() {
        var id=$(this).attr("data-id");
        $('#id_d').val(id);

    });
    $(document).on("click", "#upgrade", function() {
        $.ajax({
            url: "assets/scripts/backend/batch.php",
            type: "POST",
            cache: false,
            data:{
                type:5,
                id: $("#id_d").val()
            },
            success: function(dataResult){
                $('#disableBatchModal').modal('hide');
                location.reload();
            }
        });
    });
    $(document).on('click','#btn-add-rep',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#user_rep_form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/batch.php",
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $('#addRepModal').modal('hide');
                        alert('Data added successfully !');
                        location.reload();
                    }
                    else if (dataResult.statusCode == 201) {
                        alert(dataResult);
                    }
                }
            });
        }
        else{
            $("#user_form")[0].reportValidity();
        }
    });
    $(document).on('click','.updateRep',function(e) {
        var id = $(this).attr("data-id");
        var bno = $(this).attr("data-bno");
        var stn = $(this).attr("data-stn");
        var sti = $(this).attr("data-sti");
        var snm = $(this).attr("data-snm");
        var snt = $(this).attr("data-snt");
        var sem = $(this).attr("data-sem");
        $('#id_u').val(id);
        $('#bno_u').val(bno);
        $('#bst_no').val(stn);
        $('#bst_in').val(sti);
        $('#bst_nm').val(snm);
        $('#bst_nt').val(snt);
        $('#bst_em').val(sem);
    });

    <!-- Update -->
    $(document).on('click','#btn-update-rep',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#update_rep_form").serialize();
            //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/batch.php",
                success: function(dataResult){
                    //alert(dataResult);
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        $('#update_rep_form').modal('hide');
                        alert('Data updated successfully !');
                        location.reload();
                    }
                    else if(dataResult.statusCode==201){
                        alert(dataResult);
                    }
                }
            });
        }
        else{
            $("#user_form")[0].reportValidity();
        }
    });
    <!-- disable -->
    $(document).on('click','.disableRep',function(e) {
        //alert('ccc');
        var id = $(this).attr("data-idr");
        //alert(id);
        $('#id_dr').val(id);
    });
    $(document).on('click','#btn-disable-rep',function(e) {
        //alert('ok');
            $.ajax({
                data:{
                    type:10,
                    id: $("#id_dr").val()
                },
                type: "post",
                url: "assets/scripts/backend/batch.php",
                success: function(dataResult){
                    $('#disableBatchRepModal').modal('hide');
                    location.reload();
                }
            });
    });

    //ADD BATCH REPRESENTATIVES INTO THE BATCH
    $(document).on('click','#updateRepMod',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#update_form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/batch.php",
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        alert('Data updated successfully !');
                        $('#editRepModModal').modal('hide');
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
            url: "assets/scripts/backend/batch.php",
            type: "POST",
            cache: false,
            data:{
                type:9,
                id: $("#id_d").val()
            },
            success: function(dataResult){
                $('#deleteRepModModal').modal('hide');
                location.reload();
            }
        });
    });


