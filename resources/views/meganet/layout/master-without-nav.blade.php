<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('meganet.layout.title-meta')
    @include('meganet.layout.head')
</head>

<body class="authentication-bg">
    @yield('content')
    @include('meganet.layout.vendor-scripts')
</body>
</html>
