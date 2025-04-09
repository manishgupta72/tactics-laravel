<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel React with Inertia.js</title>
    @yield('Common-Css', View::make('IncludesFiles.Common-Css'))
    @viteReactRefresh
    @vite(['resources/js/app.jsx', 'resources/css/app.css'])
</head>

<body class="antialiased">
    @inertia
    @yield('Common-Js', View::make('IncludesFiles.Common-Js'))
</body>

</html>