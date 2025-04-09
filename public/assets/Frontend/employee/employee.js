$(function () {
    $(document).on("click", ".create_employee_page_data", function (e) {
        e.preventDefault();

        let formdata = new FormData($("#AdminFrms")[0]);
        let button = $(this);

        // **Prevent multiple submissions**
        button.prop("disabled", true);

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
            },
            success: function (data) {
                if (data.warning) {
                    // **Show warning modal if Aadhaar exists**
                    $("#existingName").text(data.employee.full_name);
                    $("#existingEmpCode").text(data.employee.emp_code);
                    $("#existingMobile").text(data.employee.emp_mobile);
                    $("#existingAadhaar").text(data.employee.emp_aadhar);
                    $("#existingStatus").text(
                        data.employee.emp_status === 1 ? "Inactive" : "Active"
                    );

                    $("#warningModal").modal("show");
                    button.prop("disabled", false);
                } else {
                    // **Show success message and reload if needed**
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.msg,
                    }).then(() => {
                        if (data.TargetURL) {
                            window.location.href = data.TargetURL;
                        } else {
                            // **Reset the form only if not updating**
                            if (
                                !$("input[name='id']").val() ||
                                $("input[name='id']").val() == "0"
                            ) {
                                $("#AdminFrms")[0].reset(); // reset input values
                                $(".select2").val(null).trigger("change"); // reset select2 fields
                            }
                        }
                    });

                    $("#warningModal").modal("hide");
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: xhr.responseJSON?.msg || "An error occurred.",
                });
                button.prop("disabled", false);
            },
            complete: function () {
                $.unblockUI();
            },
        });
    });

    // Fetch Data for DataTable
    fetch_data("no");

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
            columnDefs: [{ orderable: false, targets: [6] }],
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

    // Delete Employee
    $(document).on("click", ".delete_row", function (e) {
        let id = $(this).data("id");
        let full_name = $(this).data("full_name");

        if (id) {
            // First Confirmation Step
            Swal.fire({
                title: "Are you sure?",
                text: `Deleting Employee: ${full_name} will also remove all related data.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Next",
                cancelButtonText: "Cancel",
            }).then((firstResult) => {
                if (firstResult.isConfirmed) {
                    // Second Confirmation Step
                    Swal.fire({
                        title: "Confirm Deletion",
                        html: `<p>Are you sure you want to delete <b>${full_name}</b>?</p>
                           <p>This action is irreversible and will delete all related data of the employee.</p>`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, Delete",
                        cancelButtonText: "Cancel",
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            // AJAX request to delete the employee
                            $.ajax({
                                type: "POST",
                                url: del_route, // Make sure del_route is defined in your script
                                data: { id: id },
                                dataType: "json",
                                beforeSend: function () {
                                    ui_block(); // Assuming ui_block() shows a loading indicator
                                },
                                success: function (data) {
                                    $.unblockUI(); // Remove loading indicator

                                    if (data.msg) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Success",
                                            text: data.msg,
                                        });
                                        dataTable.ajax.reload(null, false); // Reload table data without resetting pagination
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: data.msg,
                                        });
                                    }
                                },
                                error: function () {
                                    $.unblockUI(); // Remove loading indicator
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "An unexpected error occurred.",
                                    });
                                },
                            });
                        } else if (
                            secondResult.dismiss === Swal.DismissReason.cancel
                        ) {
                            Swal.fire({
                                icon: "info",
                                title: "Cancelled",
                                text: "Deletion process has been cancelled.",
                            });
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Invalid Employee ID",
                text: "Unable to proceed with deletion.",
            });
        }
    });

    // View Employee Details
    $(document).on("click", ".view-employee-btn", function (e) {
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
                        $("#employeeDetailsContent").html(response.html);
                        $("#employeeDetailsOffcanvas").offcanvas("show");
                    } else {
                        Swal.fire({
                            icon: "error",
                            text:
                                response.msg ||
                                "Unable to fetch employee details.",
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        text: "An error occurred while fetching employee details.",
                    });
                },
            });
        }
    });
});
