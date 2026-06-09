	<!-- Add batch -->
	$(document).on('click','#btn-add',function(e) {
		var data = $("#user_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "assets/scripts/backend/batch.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#addBatchModal').modal('hide');
						alert('Data added successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert(dataResult);
					}
			}
		});
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
