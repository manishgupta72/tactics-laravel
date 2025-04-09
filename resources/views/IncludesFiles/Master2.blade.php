<!doctype html>
<html lang="en">

<head>

    <!-- Summernote CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/summernote/summernote-bs4.css') }}">
    @stack('scripts')
</head>

<body data-sidebar="colored">
    <div id="layout-wrapper">


        <div class="main-content">
            @yield('container')
        </div>
    </div>

    <script src="{{ asset('assets/libs/summernote/summernote-bs4.js') }}"></script>

    @yield('file_script')

    @stack('footer_scripts')

</body>

</html>