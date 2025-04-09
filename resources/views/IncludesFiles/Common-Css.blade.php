<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="app-url" content="{{ getBaseURL() }}">
<meta name="file-base-url" content="{{ getFileBaseURL() }}">

<link rel="stylesheet" href="{{ static_asset('assets/libs/chartist/chartist.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/spectrum-colorpicker2/spectrum.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/icons.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/app.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/custom.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-duallistbox.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Summernote CSS -->
<link rel="stylesheet" href="{{ asset('assets/libs/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select/dist/css/bootstrap-select.min.css">

<script>
    var base_url = '{{ getBaseURL() }}';
    var admin_base_url = '{{ admin_getBaseURL() }}';
    var images_url = '{{ static_asset('assets') }}';
</script>
