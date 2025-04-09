$(function () {
    // Function to store the active tab in local storage
    function storeActiveTab(tabId) {
        localStorage.setItem("activeTab", tabId);
    }

    // Function to restore the active tab from local storage
    function restoreActiveTab() {
        const activeTab = localStorage.getItem("activeTab");
        if (activeTab) {
            $(`a[href="${activeTab}"]`).tab("show");
        }
    }

    // Restore the active tab on page load
    restoreActiveTab();

    // Event listener to store active tab when clicked
    $(document).on("click", ".nav-tabs a", function () {
        const tabId = $(this).attr("href");
        storeActiveTab(tabId);
    });

    $(document).on("click", ".BasicEmployeeForm", function (e) {
        e.preventDefault();

        var formdata = new FormData($("#BasicEmployeeForm")[0]);

        $.ajax({
            type: "POST",
            url: employee_basic_detail_save, // Route defined in your Laravel routes
            enctype: "multipart/form-data",
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $(".BasicEmployeeForm").prop("disabled", true);
            },
            success: function (response) {
                $(".BasicEmployeeForm").prop("disabled", false);

                if (response.msg) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.msg,
                    }).then(() => {
                        location.reload(); // Reload the page on success
                    });
                }
            },
            error: function (xhr) {
                $(".BasicEmployeeForm").prop("disabled", false);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    let errorMessage = "";

                    if (Object.keys(errors).length > 0) {
                        Object.keys(errors).forEach((field) => {
                            errorMessage += errors[field].join("<br>") + "<br>";
                        });
                    } else {
                        errorMessage =
                            xhr.responseJSON?.msg || "Validation failed.";
                    }

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
            },
        });
    });

    $(document).on("click", ".SaveBankDetails", function (e) {
        e.preventDefault();

        var formdata = new FormData($("#BankEmployeeForm")[0]);

        $.ajax({
            type: "POST",
            url: employee_bank_detail_save, // Route defined in your Laravel routes
            enctype: "multipart/form-data",
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $(".SaveBankDetails").prop("disabled", true);
            },
            success: function (response) {
                $(".SaveBankDetails").prop("disabled", false);

                if (response.msg) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.msg,
                    }).then(() => {
                        location.reload(); // Reload the page on success
                    });
                }
            },
            error: function (xhr) {
                $(".SaveBankDetails").prop("disabled", false);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.msg || [];
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: errors.join("<br>"),
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "An unexpected error occurred.",
                    });
                }
            },
        });
    });

    $(document).on("click", ".SavePFESICDetails", function (e) {
        e.preventDefault();

        var formdata = new FormData($("#PFESICForm")[0]);

        $.ajax({
            type: "POST",
            url: employee_pf_detail_save, // Route defined in your Laravel routes
            enctype: "multipart/form-data",
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $(".SavePFESICDetails").prop("disabled", true);
            },
            success: function (response) {
                $(".SavePFESICDetails").prop("disabled", false);

                if (response.msg) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.msg,
                    }).then(() => {
                        location.reload(); // Reload the page on success
                    });
                }
            },
            error: function (xhr) {
                $(".SavePFESICDetails").prop("disabled", false);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.msg || [];
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: errors.join("<br>"),
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "An unexpected error occurred.",
                    });
                }
            },
        });
    });

    $(document).on("click", ".SaveDocumentDetails", function (e) {
        e.preventDefault();

        var formdata = new FormData($("#DocumentForm")[0]);

        $.ajax({
            type: "POST",
            url: employee_save_document, // Route defined in your Laravel routes
            enctype: "multipart/form-data",
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $(".SaveDocumentDetails").prop("disabled", true);
            },
            success: function (response) {
                $(".SaveDocumentDetails").prop("disabled", false);

                if (response.msg) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.msg,
                    }).then(() => {
                        location.reload(); // Reload the page on success
                    });
                }
            },
            error: function (xhr) {
                $(".SaveDocumentDetails").prop("disabled", false);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.msg || [];
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: errors.join("<br>"),
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "An unexpected error occurred.",
                    });
                }
            },
        });
    });
    // Delete Document
    $(document).on("click", ".delete-document", function () {
        const id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this document!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: `/tactics/backend/delete-document/${id}`,
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: response.msg,
                        }).then(() => {
                            location.reload(); // Reload the page after deletion
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to delete document.",
                        });
                    },
                });
            }
        });
    });
});

$(document).ready(function () {
    const emp_id = $("input[name='emp_id']").val();

    const fetchDocumentsUrl = `/tactics/backend/get-documents/${emp_id}`;

    function fetchDocuments() {
        $.ajax({
            type: "GET",
            url: fetchDocumentsUrl,
            success: function (response) {
                if (response.documents && response.documents.length > 0) {
                    let html = `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Document Type</th>
                                    <th>View</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    response.documents.forEach((doc) => {
                        const filePath = `${window.location.origin}/tactics/public/assets/emp_document/${doc.emp_file}`;
                        html += `
                            <tr>
                                <td>${doc.emp_doc_type}</td>
                                <td>
                                    <a href="${filePath}" target="_blank" class="btn btn-link">View</a>
                                </td>
                                <td>
                                    <button class="btn btn-danger delete-document" data-id="${doc.emp_doc_id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });

                    html += "</tbody></table>";
                    $("#documentList").html(html);
                } else {
                    $("#documentList").html(
                        "<p>No documents found for this employee.</p>"
                    );
                }
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    $("#documentList").html(
                        "<p>No documents found for this employee.</p>"
                    );
                } else {
                    $("#documentList").html("<p>Failed to load documents.</p>");
                }
            },
        });
    }

    fetchDocuments(); // Fetch documents on page load
});
