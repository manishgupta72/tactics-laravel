<script src="{{ static_asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/peity/jquery.peity.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/chartist/chartist.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/chartist-plugin-tooltips/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ static_asset('assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ static_asset('assets/js/app.js') }}"></script>
<script src="{{ static_asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/admin-resources/bootstrap-filestyle/bootstrap-filestyle.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ static_asset('assets/js/pages/form-advanced.init.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/jszip/jszip.min.js') }}"></script>
<!-- Specific Page Vendor -->
<script src="{{ static_asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ static_asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ static_asset('assets/js/pages/datatables.init.js') }}"></script>
<script src="{{ static_asset('assets/js/jquery.blockUI.js') }}"></script>
<script src="{{ static_asset('assets/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
<script src="{{ static_asset('assets/js/SweetAlert2.min.js') }}"></script>
<script src="{{ static_asset('assets/Frontend/CommonScript.js') }}"></script>
<!-- Dropzone, Markdown, and Summernote -->

<script src="{{ asset('assets/libs/summernote/summernote-bs4.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
{{-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

<div id="myModal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Modal Heading
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect"
                    data-bs-dismiss="modal">Close</button>
                {{-- <button type="button"
                class="btn btn-primary waves-effect waves-light">Save
                changes</button> --}}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="exceluploadModal" aria-labelledby="exceluploadModalLabel"
    style="width:700px" data-bs-backdrop="static">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="exceluploadModalLabel">Excel upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr class="border border-dark">
    <div class="offcanvas-body py-0">
        <form id="ExcelUploadFrm">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mt-4">
                            <label class="col-form-label" for="formGroupExampleInput">Select File {!! config('constant.Mandatry_filed') !!}</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xlsm,.xlsb,.xls" />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="py-2 sticky-bottom text-center">
                            <button type="submit"
                                class="btn btn-mw btn-primary btn-lg me-2 create_excel_upload_page_data">Submit</button>
                        </div>
                    </div>
                </div>

        </form>
    </div>
</div>

<script>
    $('.dual_select').bootstrapDualListbox({
        selectorMinimalHeight: 160
    });

    //   $('.select2').select2();

    const Toast = Swal.mixin({

        toast: true,

        position: 'top-end',

        showConfirmButton: false,

        timer: 3000,

        timerProgressBar: true,

        didOpen: (toast) => {

            toast.addEventListener('mouseenter', Swal.stopTimer)

            toast.addEventListener('mouseleave', Swal.resumeTimer)

        }

    });



    var NUMERIC_REGEX = /[0-9]/g;

    var DECIMAL_REGEX = /^\d+(\.\d{1,2})?$/;

    var EMAIL_REGEX =

        /(^[-!#$%&'*+/=?^_`{}|~0-9A-Z]+(\.[-!#$%&'*+/=?^_`{}|~0-9A-Z]+)*|^"([\001-\010\013\014\016-\037!#-\[\]-\177]|\\[\001-\011\013\014\016-\177])*")@((?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)$)|\[(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\]$/i;

    var PASSWORD_REGEX = /[a-z].*[0-9]|[0-9].*[a-z]/i;



    var isNumberKey = function(evt) {

        var charCode = (evt.which) ? evt.which : evt.keyCode

        if (charCode > 31 && (charCode < 48 || charCode > 57)) {

            return false;

        } else {

            return true;

        }

    }

    function convertToUppercase(evt) {
        const inputValue = evt.target.value.toUpperCase();
        evt.target.value = inputValue;
    }




    var isAlphabetKey = function(e) {

        var regex = new RegExp("^[a-zA-Z]+$");

        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);

        if (regex.test(str)) {

            return true;

        } else {

            e.preventDefault();

            $('.error').show();

            $('.error').text('Please Enter Alphabate');

            return false;

        }

    }



    var isDecimalKey = function(el, evt) {

        var charCode = (evt.which) ? evt.which : evt.keyCode;

        var number = el.value.split('.');



        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {

            return false;

        }



        if (number.length > 1 && charCode == 46) {

            return false;

        }



        if (charCode != 8) {



            var caratPos = getSelectionStart(el);

            var dotPos = el.value.indexOf(".");

            if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {

                return false;

            }

        }



        return true;

    }





    var txtAlphaNumeric = function(e) {

        var k = e.which;

        var ok = k >= 65 && k <= 90 || // A-Z

            k >= 96 && k <= 105 || // a-z

            k >= 35 && k <= 40 || // arrows

            k == 8 || // Backspaces

            k >= 48 && k <= 57; // 0-9



        if (!ok) {

            e.preventDefault();

        }

    }
</script>
