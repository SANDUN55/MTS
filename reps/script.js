$(document).on("click", ".edit", function() {
    var id = $(this).attr("data-id");
    var str = $(this).attr("data-val");
    $('#id_d').val(id);
    $('#id_v').text(str);
});
$(document).on("click", "#btnClick", function() {
    var st = $(this).val();
   // alert(st);
    var data = $("#repsComments").serialize();
    //alert(data);
    $.ajax({
        url: "script.php",
        type: "POST",
        cache: false,
        data:{
            type: st,
            data: data
        },
        success: function (dataResult) {
          //alert(dataResult);
            var dataResult = JSON.parse(dataResult);
            //alert(dataResult);
            if (dataResult.statusCode == 200) {
                alert('Data updated successfully !');
                $('#commentModal').modal('hide');
                location.reload();
            }
            else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        }
    });
});