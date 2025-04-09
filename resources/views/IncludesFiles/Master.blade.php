<!doctype html>
<html lang="en">

<head>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ $title }}</title>
    @yield('Common-Css', View::make('IncludesFiles.Common-Css'))

    @stack('scripts')
</head>

<body data-sidebar="colored">
    <div id="layout-wrapper">

        @yield('Header', View::make('IncludesFiles.Header'))
        @yield('sidebar', View::make('IncludesFiles.sidebar'))
        <div class="main-content">
            @yield('container')
        </div>
    </div>
    @stack('modal_files')
    @yield('Common-Js', View::make('IncludesFiles.Common-Js'))
    @yield('common_alert', View::make('IncludesFiles.common_alert'))
    @yield('file_script')
    @include('IncludesFiles.datatable_enter_search')
    @stack('footer_scripts')
</body>

</html>