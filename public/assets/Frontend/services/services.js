$(function () {
    // Load Add Service Form in Offcanvas
    $(document).on("click", ".add-service-btn", function (e) {
        e.preventDefault();
        $("#saveServiceContent").html("<p>Loading...</p>");
        $("#saveServiceOffcanvasLabel").text("Add Service");
        $("#saveServiceOffcanvas").offcanvas("show");

        $.ajax({
            type: "GET",
            url: edit_route,
            beforeSend: function () {
                ui_block();
            },
            success: function (response) {
                $.unblockUI();
                $("#saveServiceContent").html(response);
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

    // Load Edit Service Form in Offcanvas
    $(document).on("click", ".edit-service-btn", function (e) {
        e.preventDefault();

        let id = $(this).data("id");
        if (id) {
            $("#saveServiceContent").html("<p>Loading...</p>");
            $("#saveServiceOffcanvasLabel").text("Edit Service");
            $("#saveServiceOffcanvas").offcanvas("show");

            $.ajax({
                type: "GET",
                url: `${edit_route}/EID/${id}`,
                beforeSend: function () {
                    ui_block();
                },
                success: function (response) {
                    $.unblockUI();
                    $("#saveServiceContent").html(response);
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
                text: "Invalid Service ID.",
            });
        }
    });

    // Reload Data
    $(document).on("click", ".reload_data", function () {
        $("#ell-table").DataTable().ajax.reload(null, false);
    });

    // Refresh page when offcanvas is closed
    $(document).on("hidden.bs.offcanvas", "#saveServiceOffcanvas", function () {
        location.reload();
    });

    // Save Service Data
    $(document).on("click", ".create_service_page_data", function (e) {
        e.preventDefault();
        let className_h_s = $($(this).attr("class").split(" ")).get(-1);
        let formdata = new FormData($("#AdminFrms")[0]);

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
                if (data.msg !== "") {
                    if (data.msgsuc === "bg-success") {
                        Toast.fire({
                            icon: "success",
                            title: data.msg,
                        });

                        if (data.TargetURL !== "") {
                            setTimeout(function () {
                                location.href = data.TargetURL;
                            }, 500);
                        }
                    } else {
                        Swal.fire({
                            icon: "error",
                            text: data.msg,
                        });
                    }
                    $("." + className_h_s).show();
                    $.unblockUI();
                }
            },
            error: function (xhr) {
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

    // Fetch Data for DataTable
    function fetch_data(is_date_search, date = "") {
        dataTable = $("#ell-table").DataTable({
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
            columnDefs: [{ orderable: false, targets: [2] }],
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
            drawCallback: function () {
                $.unblockUI();
            },
        });
    }
    fetch_data("no");

    // Delete Service
    $(document).on("click", ".delete-service-btn", function (e) {
        let id = $(this).data("id");
        let service_name = $(this).data("service_name");

        if (isValidNumericValue(id)) {
            Swal.fire({
                title: "Are you sure?",
                text: `Confirm delete for ${service_name}?`,
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
                            if (data.msg) {
                                if (data.msgsuc === "bg-success") {
                                    Toast.fire({
                                        icon: "success",
                                        title: data.msg,
                                    });
                                    dataTable.ajax.reload(null, false); // Reload table
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
                title: "Something went wrong. Please try again later.",
            });
        }
    });

    // View Service Details
    $(document).on("click", ".view_service", function (e) {
        e.preventDefault();
        let id = $(this).data("id");

        if (id) {
            $.ajax({
                type: "POST",
                url: view_route,
                data: { id: id },
                dataType: "json",
                beforeSend: function () {
                    ui_block();
                },
                success: function (response) {
                    $.unblockUI();
                    if (response.msgsuc === "bg-success") {
                        $("#serviceDetailsContent").html(response.html);
                        $("#serviceDetailsOffcanvas").offcanvas("show");
                    } else {
                        Swal.fire({
                            icon: "error",
                            text: response.msg || "Unable to fetch service details.",
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        text: "An error occurred while fetching service details.",
                    });
                },
            });
        }
    });
});
