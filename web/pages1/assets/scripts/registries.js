	< !--Add batch-- >
    $(document).on('click', '#btn-add', function (e) {
        var valid = this.form.checkValidity();
        if (valid) {
            event.preventDefault();
            var data = $("#user_form").serialize();
            $.ajax({
            	< !--Add batch-- >
            $(document).on('click', '#btn-add', function (e) {
                var valid = this.form.checkValidity();
                if (valid) {
                    event.preventDefault();
                    var data = $("#user_form").serialize();
                    $.ajax({
                        data: data,
                        type: "post",
                        url: "assets/scripts/backend/registries.php",
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $('#addHolidays').modal('hide');
                                alert('Data added successfully !');
                                location.reload();
                            }
                            else if (dataResult.statusCode == 201) {
                                alert(dataResult);
                            }
                        }
                    });
                } else {
                    $("#user_form")[0].reportValidity()
                }
            });
            $(document).on('click', '.update', function (e) {
                var id = $(this).attr("data-id");
                var mc = $(this).attr("data-mc");
                var mn = $(this).attr("data-mn");
                var mp = $(this).attr("data-mp");
                $('#id_u').val(id);
                $('#mc_u').val(mc);
                $('#mn_u').val(mn);
                $('#mp_u').val(mp);
            });

            	< !--Update -->
                $(document).on('click', '#update', function (e) {
                    var valid = this.form.checkValidity();
                    if (valid) {
                        event.preventDefault();
                        var data = $("#update_form").serialize();
                        $.ajax({
                            data: data,
                            type: "post",
                            url: "assets/scripts/backend/registries.php",
                            success: function (dataResult) {
                                var dataResult = JSON.parse(dataResult);
                                if (dataResult.statusCode == 200) {
                                    $('#editHolModal').modal('hide');
                                    alert('Data updated successfully !');
                                    location.reload();
                                }
                                else if (dataResult.statusCode == 201) {
                                    alert(dataResult);
                                }
                            }
                        });
                    } else {
                        $("#update_form")[0].reportValidity();
                    }
                });
            $(document).on("click", ".delete", function () {
                var id = $(this).attr("data-id");
                $('#id_d').val(id);

            });
            $(document).on("click", "#delete", function () {
                $.ajax({
                    url: "assets/scripts/backend/registries.php",
                    type: "POST",
                    cache: false,
                    data: {
                        type: 3,
                        id: $("#id_d").val()
                    },
                    success: function (dataResult) {
                        $('#deleteHolModal').modal('hide');
                        $("#" + dataResult).remove();
                        location.reload();
                    }
                });
            });
            //VISITING STAFF REGISTRY
            < !--Add batch-- >
                $(document).on('click', '#btn-add-visiting-staff', function (e) {
                    alert('ok');
                    var valid = this.form.checkValidity();
                    if (valid) {
                        event.preventDefault();
                        var data = $("#user_vs_form").serialize();
                        alert(data);
                        $.ajax({
                            data: data,
                            type: "post",
                            url: "assets/scripts/backend/registries.php",
                            success: function (dataResult) {
                                alert(dataResult);
                                var dataResult = JSON.parse(dataResult);
                                if (dataResult.statusCode == 200) {
                                    $('#addHolidays').modal('hide');
                                    alert('Data added successfully !');
                                    location.reload();
                                }
                                else if (dataResult.statusCode == 201) {
                                    alert(dataResult);
                                }
                            }
                        });
                    } else {
                        $("#user_form")[0].reportValidity()
                    }
                });
            $(document).on('click', '.updateVS', function (e) {
                var id = $(this).attr("data-id");
                var mf = $(this).attr("data-fnm");
                var ms = $(this).attr("data-snm");
                var me = $(this).attr("data-eml");
                $('#id_u').val(id);
                $('#mf_u').val(mf);
                $('#ms_u').val(ms);
                $('#me_u').val(me);
            });
                < !--Update -->
                $(document).on('click', '#btn-update-visiting-staff', function (e) {
                    var valid = this.form.checkValidity();
                    if (valid) {
                        event.preventDefault();
                        var data = $("#update_vs_form").serialize();
                        $.ajax({
                            data: data,
                            type: "post",
                            url: "assets/scripts/backend/registries.php",
                            success: function (dataResult) {
                                var dataResult = JSON.parse(dataResult);
                                if (dataResult.statusCode == 200) {
                                    $('#editHolModal').modal('hide');
                                    alert('Data updated successfully !');
                                    location.reload();
                                }
                                else if (dataResult.statusCode == 201) {
                                    alert(dataResult);
                                }
                            }
                        });
                    } else {
                        $("#update_form")[0].reportValidity();
                    }
                });
            $(document).on("click", ".disableVS", function () {
                var id = $(this).attr("data-id");
                $('#id_vd').val(id);

            });
            $(document).on("click", "#disable-visiting-staff", function () {
                $.ajax({
                    url: "assets/scripts/backend/registries.php",
                    type: "POST",
                    cache: false,
                    data: {
                        type: 7,
                        id: $("#id_vd").val()
                    },
                    success: function (dataResult) {
                        $('#disableVisitingStaff').modal('hide');
                        $("#" + dataResult).remove();
                        location.reload();
                    }
                });
            });
            $btn.prop('disabled', true);
            $.ajax({
                url: "assets/scripts/backend/registries.php",
                type: "POST",
                cache: false,
                data: {
                    type: 7,
                    id: $("#id_vd").val()
                },
                complete: function () { $btn.prop('disabled', false); },
                success: function (dataResult) {
                    $('#disableVisitingStaff').modal('hide');
                    try { var removedId = JSON.parse(dataResult); } catch (e) {
                        // fallback: server might return id directly
                        var removedId = dataResult;
                    }
                    $("#" + removedId).remove();
                    location.reload();
                },
                error: function () { alert('Request failed'); }
            });
        });