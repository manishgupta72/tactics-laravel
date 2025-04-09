$(function () {
    // Load Add Job Opening Form in Offcanvas
    $(document).on("click", ".add-jobopening-btn", function (e) {
        e.preventDefault();
        $("#saveJobOpeningContent").html("<p>Loading...</p>");
        $("#saveJobOpeningOffcanvasLabel").text("Add Job Opening");
        $("#saveJobOpeningOffcanvas").offcanvas("show");

        $.ajax({
            type: "GET",
            url: edit_route,
            beforeSend: function () {
                ui_block();
            },
            success: function (response) {
                $.unblockUI();
                $("#saveJobOpeningContent").html(response);
            },
            error: function () {
                $.unblockUI();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to load the form. Please try again.",
                });
            },
        });
    });

    // Load Edit Job Opening Form in Offcanvas
    $(document).on("click", ".edit-jobopening-btn", function (e) {
        e.preventDefault();

        let id = $(this).data("id");
        console.log(id);
        if (id) {
            $("#saveJobOpeningContent").html("<p>Loading...</p>");
            $("#saveJobOpeningOffcanvasLabel").text("Edit Job Opening");
            $("#saveJobOpeningOffcanvas").offcanvas("show");

            $.ajax({
                type: "GET",
                url: `${edit_route}/EID/${id}`,
                beforeSend: function () {
                    ui_block();
                },
                success: function (response) {
                    $.unblockUI();
                    $("#saveJobOpeningContent").html(response);
                },
                error: function () {
                    $.unblockUI();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Failed to load the form. Please try again.",
                    });
                },
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Invalid Request",
                text: "Invalid Job Opening ID.",
            });
        }
    });

    // Reload Data
    $(document).on("click", ".reload_data", function () {
        $("#jobopening-table").DataTable().ajax.reload(null, false);
    });

    // Refresh page when offcanvas is closed
    $(document).on("hidden.bs.offcanvas", "#saveJobOpeningOffcanvas", function () {
        location.reload();
    });

    // Save Job Opening
    $(document).on("click", ".create_jobopening_page_data", function (e) {
        e.preventDefault();
        let className_h_s = $($(this).attr("class").split(" ")).get(-1);
        var formdata = new FormData($("#JobOpeningForm")[0]);

        $.ajax({
            type: "POST",
            url: save_route,
            enctype: "multipart/form-data",
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                ui_block();
                $("." + className_h_s).hide();
            },
            success: function (data) {
                if (data.msg != "") {
                    if (data.msgsuc == "bg-success") {
                        Toast.fire({
                            icon: "success",
                            title: data.msg,
                        });

                        if (data.TargetURL != "") {
                            setTimeout(function () {
                                location.href = data.TargetURL;
                            }, 500);
                        }
                    } else if (data.msgfail == "bg-danger") {
                        if (data.msgtype != "" && data.msgtype != null) {
                            if (
                                data.msgtype == "val_error" &&
                                data.msgtype != null
                            ) {
                                var msg_title =
                                    data.msgtype_title.length > 0
                                        ? data.msgtype_title
                                        : "";
                                Swal.fire({
                                    icon: "warning",
                                    title: msg_title,
                                    html: data.msg,
                                });
                            } else {
                                Swal.fire({
                                    icon: "warning",
                                    text: data.msg,
                                });
                            }
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data.msg,
                            });
                        }
                    }
                    $("." + className_h_s).show();
                    $.unblockUI();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $.unblockUI();

                if (xhr.status === 422) {
                    let validationErrors = xhr.responseJSON.msg;
                    let errorMessage = Array.isArray(validationErrors)
                        ? validationErrors.join("<br>")
                        : validationErrors;

                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: errorMessage,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "An unexpected error occurred.",
                    });
                }

                $("." + className_h_s).show();
            },
        });
    });

    fetch_data("no");

    function fetch_data(is_date_search, date = "") {
        dataTable = $("#jobopening-table").DataTable({
            responsive: true,
            searching: true,
            info: true,
            ordering: true,
            autoWidth: false,
            lengthMenu: [
                [10, 50, 25],
                [10, 50, 25],
            ],
            dom: '<"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
            columnDefs: [{ orderable: false, targets: [5] }],
            processing: true,
            serverSide: true,
            order: [
                [0, "desc"],
                [1, "asc"],
            ],
            ajax: {
                url: list_route,
                type: "POST",
                data: {
                    is_date_search: is_date_search,
                    date,
                },
                beforeSend: function () {
                    ui_block();
                },
            },
            drawCallback: function (settings) {
                $.unblockUI();
            },
        });
    }

    // Delete Job Opening
    $(document).on("click", ".delete_jobopening", function (e) {
        let id = $(this).data("id");
        let job_title = $(this).data("job_title");
        if (isValidNumericValue(id)) {
            Swal.fire({
                title: "Are you sure?",
                text: `Confirm Delete ${job_title}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: del_route,
                        data: { id: id },
                        dataType: "json",
                        beforeSend: function () {
                            ui_block();
                        },
                        success: function (data) {
                            if (data.msg != "") {
                                if (data.msgsuc === "bg-success") {
                                    Toast.fire({
                                        icon: "success",
                                        title: data.msg,
                                    });
                                    dataTable.ajax.reload(null, false);
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        text: data.msg,
                                    });
                                }
                            }
                            $.unblockUI();
                        },
                        error: function () {
                            $.unblockUI();
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "An unexpected error occurred.",
                            });
                        },
                    });
                }
            });
        } else {
            Toast.fire({
                icon: "error",
                title: "Invalid Job Opening ID.",
            });
        }
    });
});
