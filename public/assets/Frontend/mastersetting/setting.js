$(function () {


    $(document).on('click', '.GeneralSettingFrmBtn', function (e) {
        e.preventDefault();
        const formId = $(this).closest('form').attr('id');
        className_h_s = $($(this).attr("class").split(" ")).get(-1);
        var formdata = new FormData($(`#${formId}`)[0]);
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

                        return false;
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
                url: lst_master_route,
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

   


    $(document).on('click', '.create_master_data_page_data', function (e) {
        e.preventDefault();
        className_h_s = $($(this).attr("class").split(" ")).get(-1);
        var formdata = new FormData($('#MasterFrm')[0]);
        $.ajax({
            type: "POST",
            url: save_master_route,
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
                        dataTable.ajax.reload(null, false)
                        $('#MasterFrm')[0].reset();
                        $("#MasterFrm #master_data_description").text('');
                        $("#MasterFrm .add_master_data").hide();

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

    $(document).on('click', '.add_master_data,.reset_master_data', function (e) {
        $('#MasterFrm')[0].reset();
        $("#MasterFrm #master_data_id").val(0);
        $("#MasterFrm #master_data_description").text('');
        $(this).hide();
    });

    $(document).on('click', '.edit-row', function (e) {
        var master_data_id = $(this).data('master_data_id');
        if (isValidNumericValue(master_data_id)) {
            $.ajax({
                type: "POST",
                url: edt_master_route,
                data: { 'master_data_id': master_data_id },
                dataType: "json",
                beforeSend: function () {
                    ui_block();
                },
                success: function (data) {
                    if (data.msg != '') {
                        if (data.msgsuc == 'bg-success') {
                            if (isValidObject(data.contact_details)) {
                                var master_data_id              = data.contact_details.master_data_id;
                                var master_data_name            = data.contact_details.master_data_name;
                                var master_data_description     = data.contact_details.master_data_description;
                                var mid                         = data.contact_details.mid;

                                $("#MasterFrm #master_data_name").val(master_data_name);
                                $("#MasterFrm #master_data_description").text(master_data_description);
                                $("#MasterFrm #mid").val(mid);
                                $("#MasterFrm #master_data_id").val(master_data_id);
                                $("#MasterFrm .add_master_data").show();
                                // $("#faqaddModal #faq_status").val(faq_status).prop("disabled", false);;
                                // $('#faqaddModal #faq_description').summernote('code', faq_description);
                                // $('#faqaddModal #faq_description').summernote('enable');
                                // $("#faqaddModal #faqaddModalLabel").html('Edit Faq detail');

                            }

                        } else if (data.msgfail == 'bg-danger') {
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

    $(document).on('click', '.delete-row', function (e) {
        var master_data_id                  = $(this).data('master_data_id');
        var master_data_name               = $(this).data('master_data_name');
        if (isValidNumericValue(master_data_id) ) {
            Swal.fire({
                title: 'Are you sure ?',
                text: `Confirm Delete ${master_data_name} ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: del_master_route,
                        data: { 'master_data_id': master_data_id},
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
                                            var msg_title = data.msgtype_title.length > 0 ?  data.msgtype_title : "";
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
                        error: function(jqXHR, textStatus, errorThrown) {
                            var  className_h_s;
                            alert_error(jqXHR, textStatus, errorThrown,className_h_s);
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

    // $(document).on('click', '.create_master_data_page_data', function (e) {
    //     e.preventDefault();
    //     className_h_s = $($(this).attr("class").split(" ")).get(-1);
    //     var formdata = new FormData($('#MasterFrm')[0]);
    //     $.ajax({
    //         type: "POST",
    //         url: save_master_route,
    //         enctype: 'multipart/form-data',
    //         data: formdata,
    //         contentType: false,
    //         processData: false,
    //         dataType: "json",
    //         beforeSend: function () {
    //             ui_block();
    //             $('.' + className_h_s).hide();
    //         },
    //         success: function (data) {
    //             if (data.msg != '') {
    //                 if (data.msgsuc == 'bg-success') {

    //                     Toast.fire({
    //                         icon: 'success',
    //                         title: data.msg
    //                     });
    //                     dataTable.ajax.reload(null, false)
    //                     $('#MasterFrm')[0].reset();
    //                     $("#MasterFrm #master_data_description").text('');
    //                     $("#MasterFrm .add_master_data").hide();

    //                 }
    //                 else if (data.msgfail == 'bg-danger') {
    //                     if (data.msgtype != '' && data.msgtype != null) {

    //                         if (data.msgtype == 'val_error' && data.msgtype != null) {
    //                             var msg_title = data.msgtype_title.length > 0 ? data.msgtype_title : "";
    //                             Swal.fire({
    //                                 icon: 'warning',
    //                                 // title: 'Please check following mandatory fields :',
    //                                 title: msg_title,
    //                                 html: data.msg
    //                             });
    //                         } else {
    //                             Swal.fire({
    //                                 icon: 'warning',
    //                                 //title: '".$fr_error_title."',
    //                                 text: data.msg
    //                             });
    //                         }

    //                     }
    //                     else {
    //                         Toast.fire({
    //                             icon: 'error',
    //                             title: data.msg
    //                         });
    //                     }
    //                 }
    //                 $('.' + className_h_s).show();
    //                 $.unblockUI();
    //             }
    //         },
    //         error: function (jqXHR, textStatus, errorThrown) {
    //             alert_error(jqXHR, textStatus, errorThrown, className_h_s);
    //         }
    //     });
    // });


  

    $(document).on('click', '.create_change_password_page_data', function (e) {
        e.preventDefault();
        className_h_s = $($(this).attr("class").split(" ")).get(-1);
        var formdata = new FormData($('#ChangePasswordFrm')[0]);
        $.ajax({
            type: "POST",
            url: password_route,
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

});