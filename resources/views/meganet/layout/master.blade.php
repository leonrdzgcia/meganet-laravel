<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('meganet.layout.title-meta')
    @include('meganet.layout.head')
</head>

<body>
<div id="init-vue">
    <div>
        @include('meganet.layout.topbar')
        @include('meganet.layout.sidebar')
    </div>
    <div id="layout-wrapper" class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('meganet.layout.footer')
    </div>

    @include('meganet.layout.right-sidebar')
<!-- /Right-bar -->
</div>
<!-- JAVASCRIPT -->
@include('meganet.layout.vendor-scripts')
</body>
</html>
