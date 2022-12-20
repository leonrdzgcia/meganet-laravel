@yield('css')

<!-- App favicon -->
<link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
<!-- preloader css -->
<link rel="stylesheet" href="{{ URL::asset('assets/css/preloader.min.css')}}" type="text/css"/>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css"/>
<!-- Icons Css -->
<link href="{{ URL::asset('assets/css/icons.min.css')}}" id="icons-style" rel="stylesheet" type="text/css"/>
<!-- App Css-->
<link href="{{ URL::asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/app.css')}}" id="app-style" rel="stylesheet" type="text/css"/>

@isset($packages['css'])
    @foreach($packages['css'] as $package_css)
        <link href="{{ URL::asset($package_css->url)}}" rel="stylesheet" type="text/css"/>
    @endforeach
@endisset

<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" id="app-style" rel="stylesheet" type="text/css"/>

<meta name="csrf-token" content="{{ csrf_token() }}">
