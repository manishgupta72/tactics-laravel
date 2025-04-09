$(function () {
    // Handle Add/Edit Button
    $(document).on(
        "click",
        ".add-company-btn, .edit-company-btn",
        function (e) {
            e.preventDefault();
            const id = $(this).data("id") || ""; // Get the ID if present
            $("#saveCompanyContent").html("<p>Loading...</p>");
            $("#saveCompanyOffcanvasLabel").text(
                id ? "Edit Company" : "Add Company"
            );
            $("#saveCompanyOffcanvas").offcanvas("show");

            // Dynamically form the URL based on whether it's Add or Edit
            const url = id ? `${edit_route}/EID/${id}` : edit_route;

            // Fetch the form via AJAX
            $.get(url, function (response) {
                $("#saveCompanyContent").html(response);

                // Initialize Select2 for dropdowns in the loaded form
                const $offcanvas = $("#saveCompanyOffcanvas");
                $offcanvas.find(".select2").each(function () {
                    if ($(this).data("select2")) {
                        $(this).select2("destroy"); // Destroy existing Select2 instance
                    }
                    $(this).select2({
                        dropdownParent: $offcanvas, // Ensures dropdown stays within the offcanvas
                        width: "100%", // Ensures dropdown matches the input width
                    });
                });
            }).fail(function () {
                Toast.fire({
                    icon: "warning",
                    title: "failed to load form !",
                });
            });
        }
    );

    // Fetch associate data
    fetch_data("no");

    function fetch_data(is_date_search, date = "", stds = "") {
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
            columnDefs: [
                { orderable: false, targets: [3] },
                //   {
                //       "targets": [0],
                //       "visible": false,
                //       "searchable": false
                //   },
            ],
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

    // Save Company
    $(document).on("click", ".create_company_page_data", function (e) {
        e.preventDefault();
        const formData = new FormData($("#AdminFrms")[0]);

        $.post({
            url: save_route,
            data: formData,
            processData: false,
            contentType: false,
        })
            .done(function (response) {
                if (response.msgsuc === "bg-success") {
                    Toast.fire({
                        icon: "success",
                        title: response.msg,
                    });
                    $("#saveCompanyOffcanvas").offcanvas("hide");
                    dataTable.ajax.reload();
                } else {
                    Swal.fire({
                        icon: "error",
                        text: response.msg,
                    });
                }
            })
            .fail(function (xhr) {
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
            });
    });

    // Delete Company

    $(document).on("click", ".delete_row", function (e) {
        const id = $(this).data("id");
        const company_name = $(this).data("company_name");

        if (isValidNumericValue(id)) {
            Swal.fire({
                title: "Are you sure?",
                text: `Confirm delete ${company_name}?`,
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
                                if (data.msgsuc == "bg-success") {
                                    Toast.fire({
                                        icon: "success",
                                        title: data.msg,
                                    });

                                    dataTable.ajax.reload(null, false);
                                } else if (data.msgfail == "bg-danger") {
                                    if (
                                        data.msgtype != "" &&
                                        data.msgtype != null
                                    ) {
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
                            }
                            $.unblockUI();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            var className_h_s;
                            alert_error(
                                jqXHR,
                                textStatus,
                                errorThrown,
                                className_h_s
                            );
                        },
                    });
                }
            });
        } else {
            Toast.fire({
                icon: "error",
                title: "Something went wrong try again later",
            });
        }
    });
});
