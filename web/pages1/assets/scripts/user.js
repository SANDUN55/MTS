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
<!-- Add user -->
	$(document).on('click','#btn-add',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#user_form").serialize();
            //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/user.php",
                success: function (dataResult) {
                   // alert('function');
                   //alert(dataResult);
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
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
$(document).on('click','#update',function(e) {
    var valid = this.form.checkValidity();
    if(valid) {
        event.preventDefault();
        var data = $("#update_form").serialize();
        //alert(data);
        $.ajax({
            data: data,
            type: "post",
            url: "assets/scripts/backend/user.php",
            success: function (dataResult) {
                alert(dataResult);
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
