$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#LoginFrm").on("click", function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",

            url: base_url + "admin_login",

            data: $("#LoginForm").serialize(),

            dataType: "json",

            beforeSend: function () {
                $("#LoginFrm").prop("disabled", true);

                $("#LoginFrm").html("Please Wait.....");
                ui_block();
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
                                    // title: 'Please check following mandatory fields :',
                                    title: msg_title,
                                    html: data.msg,
                                });
                            } else {
                                Swal.fire({
                                    icon: "warning",
                                    //title: '".$fr_error_title."',
                                    text: data.msg,
                                });
                            }
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data.msg,
                            });
                        }
                        $("#LoginFrm").prop("disabled", false);
                        $("#LoginFrm").html("Sign in");
                    }
                }
                $.unblockUI();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var className_h_s;
                alert_error(jqXHR, textStatus, errorThrown, className_h_s);
            },
        });
    });
});
