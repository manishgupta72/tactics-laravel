<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title }}</title>
    @yield('Common-Css', View::make('front.IncludesFiles.Common-Css'))
    @stack('scripts')
</head>

<body>

    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    @yield('Header', View::make('front.IncludesFiles.Header'))
    @yield('Header', View::make('front.IncludesFiles.app-head'))
    {{-- @yield('Header', View::make('front.IncludesFiles.side-menu')) --}}

    <div id="appCapsule" class="pb-5">
        @yield('container')
    </div>

    @yield('sidebar', View::make('front.IncludesFiles.sidebar'))
    @yield('Common-Js', View::make('front.IncludesFiles.Common-Js'))
    @yield('common_alert', View::make('front.IncludesFiles.common_alert'))
    @yield('file_script')
    @stack('footer_scripts')
</body>

</html>
