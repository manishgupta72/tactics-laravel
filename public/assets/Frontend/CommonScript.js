function AlerMsg(data, Message) {

    if (data.msg != "") {

        if (data.msgsuc == "bg-success") {

            $.toast({

                heading: "Success",

                text: data.msg,

                position: "top-right",

                stack: false,

                showHideTransition: "slide",

                icon: "success",

            });



            if (data.TargetURL != "") {

                setTimeout(function () {

                    location.href = data.TargetURL;

                    $(Message).LoadingOverlay("hide", true);

                }, 500);

            } else {

                setTimeout(function () {

                    location.reload(true);

                    $(Message).LoadingOverlay("hide", true);

                }, 500);

            }

        } else if (data.msgfail == "bg-danger") {

            $.toast({

                heading: "Error",

                text: data.msg,

                position: "top-right",

                stack: false,

                showHideTransition: "slide",

                icon: "error",

            });

            //$(Message).pleaseWait('stop');

            $(Message).LoadingOverlay("hide", true);

        }

    }

}



function alert_error(jqXHR, textStatus, errorThrown, className_h_s="") {
    var error;

    switch (jqXHR.status) {
        case 0:
            error = '<p>Error: Not connected. Verify network.</p>';
            break;
        case 400:
            error = '<p>Error: Bad Request [400]</p>';
            break;
        case 401:
            error = '<p>Error: Unauthorized [401]</p>';
            break;
        case 403:
            error = '<p>Error: Forbidden [403]</p>';
            break;
        case 404:
            error = '<p>Error: Requested page not found [404]</p>';
            break;
        case 405:
            error = '<p>Error: Method Not Allowed [405]</p>';
            break;
        case 500:
            error = '<p>Error: Internal server error [500]</p>';
            break;
        case 501:
            error = '<p>Error: The requested functionality is not implemented on this server. [501]</p>';
            break;
        case 502:
            error = '<p>Error: The server received an invalid response from the upstream server. [502]</p>';
            break;
        case 503:
            error = '<p>Error: Service Unavailable [503]</p>';
            break;
        case 504:
            error = '<p>Error: The server did not receive a timely response from the upstream server. [504]</p>';
            break;
        case 505:
            error = '<p>Error: The HTTP version used in the request is not supported by the server. [505]</p>';
            break;
        case textStatus === 'parsererror':
            error = '<p>Error: Requested JSON parse failed.</p>';
            break;
        case textStatus === 'timeout':
            error = '<p>Error: Time out error.</p>';
            break;
        case textStatus === 'abort':
            error = '<p>Error: Ajax request aborted.</p>';
            break;
        default:
            error = '<p>Error: ' + jqXHR.responseText + '</p>';
    }

    Swal.fire({
        icon: 'error',
        title: 'Server Error Occurred:',
        html: error,
        confirmButtonColor: '#d33',
    });
    if(className_h_s != ""){
        $('.' + className_h_s).show();
    }

    $.unblockUI();
}

function alert_error_front(jqXHR, textStatus, errorThrown, className_h_s="") {
    var error;

    switch (jqXHR.status) {
        case 0:
            error = '<p>Error: Not connected. Verify network.</p>';
            break;
        case 400:
            error = '<p>Error: Bad Request [400]</p>';
            break;
        case 401:
            error = '<p>Error: Unauthorized [401]</p>';
            break;
        case 403:
            error = '<p>Error: Forbidden [403]</p>';
            break;
        case 404:
            error = '<p>Error: Requested page not found [404]</p>';
            break;
        case 405:
            error = '<p>Error: Method Not Allowed [405]</p>';
            break;
        case 500:
            error = '<p>Error: Internal server error [500]</p>';
            break;
        case 501:
            error = '<p>Error: The requested functionality is not implemented on this server. [501]</p>';
            break;
        case 502:
            error = '<p>Error: The server received an invalid response from the upstream server. [502]</p>';
            break;
        case 503:
            error = '<p>Error: Service Unavailable [503]</p>';
            break;
        case 504:
            error = '<p>Error: The server did not receive a timely response from the upstream server. [504]</p>';
            break;
        case 505:
            error = '<p>Error: The HTTP version used in the request is not supported by the server. [505]</p>';
            break;
        case textStatus === 'parsererror':
            error = '<p>Error: Requested JSON parse failed.</p>';
            break;
        case textStatus === 'timeout':
            error = '<p>Error: Time out error.</p>';
            break;
        case textStatus === 'abort':
            error = '<p>Error: Ajax request aborted.</p>';
            break;
        default:
            error = '<p>Error: ' + jqXHR.responseText + '</p>';
    }

    // Swal.fire({
    //     icon: 'error',
    //     title: 'Server Error Occurred:',
    //     html: error,
    //     confirmButtonColor: '#d33',
    // });

    $('.big-errors').offcanvas('show')
    $('.big-errors .title').html('Server Error Occurred:');
    $('.big-errors .title_msg').html(error);
    if(className_h_s != ""){
        $('.' + className_h_s).show();
    }
    $.unblockUI();
}

function dataURItoBlob(dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {
        type: mimeString
    });
}



$.ajaxSetup({

    headers: {

        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),

    },

});





function replaceAll(str, find, replace) {

    var escapedFind = find.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");

    return typeof str === 'string' ? str.replace(new RegExp(escapedFind, 'g'), replace) : '';

}



function isValidNumericValue(value) {

    if (value != null && value !== undefined && $.isNumeric(value) && parseFloat(value) > 0) {

        return true;

    }

    return false;

}



function isValidObject(obj) {

    if (obj == null || obj == undefined) {

      return false;

    }

    if (Object.keys(obj).length === 0) {

      return false;

    }

    return true;

  }

  









function ui_block() {

    $.blockUI({

        css: {

            border: 'none',

            padding: '15px',

            backgroundColor: '',

            '-webkit-border-radius': '10px',

            '-moz-border-radius': '10px',

            opacity: 0.5,

            color: '#fff'

        }

    });

}







