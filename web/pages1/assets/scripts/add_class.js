 function checkTime() {
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
});

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
<!-- Add class -->
	$(document).on('click','#btn-add',function(e) {
        var valid = this.form.checkValidity();
        if(valid) {
            event.preventDefault();
            var data = $("#user_form").serialize();
           //alert(data);
            $.ajax({
                data: data,
                type: "post",
                url: "assets/scripts/backend/add_class.php",
                success: function (dataResult) {
                   //alert(dataResult);
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
