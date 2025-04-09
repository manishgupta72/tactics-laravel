$(function () {             //$(function () {  is equal to this = $(document).ready(function(){

    $(document).on('click', '.create_role_page_data', function (e) {
        e.preventDefault();
        className_h_s = $($(this).attr("class").split(" ")).get(-1);
        var formdata = new FormData($('#RoleFrms')[0]);
        $.ajax({
            type: "POST",
            url: save_route,
            enctype: 'multipart/form-data',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                ui_block();
                $('.' + className_h_s).hide();
            },
            success: function (data) {
                if (data.msg != '') {
                    if (data.msgsuc == 'bg-success') {

                        Toast.fire({
                            icon: 'success',
                            title: data.msg
                        });

                        if (data.TargetURL != "") {
                            setTimeout(function () {
                                location.href = data.TargetURL;
                            }, 500);
                        }

                    }
                    else if (data.msgfail == 'bg-danger') {
                        if (data.msgtype != '' && data.msgtype != null) {

                            if (data.msgtype == 'val_error' && data.msgtype != null) {
                                var msg_title = data.msgtype_title.length > 0 ? data.msgtype_title : "";
                                Swal.fire({
                                    icon: 'warning',
                                    // title: 'Please check following mandatory fields :',
                                    title: msg_title,
                                    html: data.msg
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    //title: '".$fr_error_title."',
                                    text: data.msg
                                });
                            }

                        }
                        else {
                            Toast.fire({
                                icon: 'error',
                                title: data.msg
                            });
                        }
                    }
                    $('.' + className_h_s).show();
                    $.unblockUI();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert_error(jqXHR, textStatus, errorThrown, className_h_s);
            }
        });
    });


    fetch_data('no');

    function fetch_data(is_date_search, date = '', stds = '') {
        dataTable = $('#ell-table').DataTable({
            "responsive": true,
            "searching": true,
            "info": true,
            "ordering": true,
            autoWidth: false,
            "lengthMenu": [[10, 50, 25], [10, 50, 25]],
            dom: '<"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
            "columnDefs": [
                // { className: "dtr-control", targets: [5] },
                { orderable: false, targets: [3] },
                // {
                //     "targets": [4],
                //     "visible": false,
                //     "searchable": false
                // },

            ],
            "processing": true,
            "serverSide": true,
            "order": [[0, 'desc'], [1, 'asc']],
            "ajax": {
                url: list_route,
                type: "POST",
                data: {
                    is_date_search: is_date_search, date
                },
                beforeSend: function () {
                    ui_block();
                },
            },
            "drawCallback": function (settings) {
                $.unblockUI();
            }
        });
    }

    $(document).on('click', '.delete_role', function (e) {
        let RollId = $(this).data('rollid');
        let RollName = $(this).data('rollname');
        if (isValidNumericValue(RollId)) {
            Swal.fire({
                title: 'Are you sure ?',
                text: `Confirm Delete ${RollName} ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: del_route,
                        data: { 'RollId': RollId },
                        dataType: "json",
                        beforeSend: function () {
                            ui_block();
                        },
                        success: function (data) {
                            if (data.msg != '') {
                                if (data.msgsuc == 'bg-success') {

                                    Toast.fire({
                                        icon: 'success',
                                        title: data.msg
                                    });

                                    dataTable.ajax.reload(null, false)

                                } else if (data.msgfail == 'bg-danger') {
                                    if (data.msgtype != '' && data.msgtype != null) {

                                        if (data.msgtype == 'val_error' && data.msgtype != null) {
                                            var msg_title = data.msgtype_title.length > 0 ? data.msgtype_title : "";
                                            Swal.fire({
                                                icon: 'warning',
                                                title: msg_title,
                                                html: data.msg
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'warning',
                                                //title: '".$fr_error_title."',
                                                text: data.msg
                                            });
                                        }

                                    } else {
                                        Toast.fire({
                                            icon: 'error',
                                            title: data.msg
                                        });
                                    }
                                }
                            }
                            $.unblockUI();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            var className_h_s;
                            alert_error(jqXHR, textStatus, errorThrown, className_h_s);
                        }
                    });
                }
            });
        }
        else {
            Toast.fire({
                icon: 'error',
                title: 'Something went wrong try again later'
            });
        }

    });

    $(document).on("change", "#Status", function () {
        if ($(this).val() != "") {
            $("#ell-table").DataTable().destroy();
            fetch_data("yes", $("#FormFrm").serialize());
        }
    });

    $(document).on('click', '.reload_data', function () {
        // dataTable.ajax.reload(null, false)
        $("#ell-table").DataTable().destroy();
        fetch_data("no");
    });

    $(document).on('click', '.CheckRoles', function (e) {
        e.preventDefault();

    });


    $(document).on('click', '.CheckRoles', function (e) {
        let RollId = $(this).data('rollid');
        let RollName = $(this).data('rollname');
        if (isValidNumericValue(RollId)) {
            $.ajax({
                type: "POST",
                url: view_permisssion,
                data: { 'RollId': RollId },
                dataType: "json",
                beforeSend: function () {
                    ui_block();
                },
                success: function (data) {
                    if (data.msg != '') {
                        if (data.msgsuc == 'bg-success') {

                            // Toast.fire({
                            //     icon: 'success',
                            //     title: data.msg
                            // });

                            $("#myModal").modal('show');
                            $("#myModal").find('.modal-header .modal-title').html('Permission Assign List');
                            $("#myModal").find('.modal-body').html(data.html);


                        } else if (data.msgfail == 'bg-danger') {
                            if (data.msgtype != '' && data.msgtype != null) {

                                if (data.msgtype == 'val_error' && data.msgtype != null) {
                                    var msg_title = data.msgtype_title.length > 0 ? data.msgtype_title : "";
                                    Swal.fire({
                                        icon: 'warning',
                                        title: msg_title,
                                        html: data.msg
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        //title: '".$fr_error_title."',
                                        text: data.msg
                                    });
                                }

                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.msg
                                });
                            }
                        }
                    }
                    $.unblockUI();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var className_h_s;
                    alert_error(jqXHR, textStatus, errorThrown, className_h_s);
                }
            });

        }
        else {
            Toast.fire({
                icon: 'error',
                title: 'Something went wrong try again later'
            });
        }

    });



});