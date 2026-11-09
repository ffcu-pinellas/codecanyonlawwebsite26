<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="description" content="@yield('meta_description')"/>
    <meta name="keywords" content="@yield('meta_keyword')"/>
    <meta property="og:title" content="{{clean($generalSetting?$generalSetting->og_meta_title:'')}}" />
    <meta property="og:description" content="{{clean($generalSetting?$generalSetting->og_meta_description:'')}}" />
    <meta property="og:image" content="{{$generalSetting?$generalSetting->og_meta_image:''}}" />
    <meta name="author" content="{{clean($generalSetting?$generalSetting->author_name:'')}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

    <!-- Favicon & PWA Application Manifest -->
    <link href="{{ $logoFavicon?$logoFavicon->favicon:'' }}" rel="shortcut icon" type="image/png">
    @php
        $portalManifest = 'client-manifest.json';
        $portalAppTitle = 'Client Portal';
        $portalThemeColor = '#fecc56';
        if (request()->is('staff*')) {
            $portalManifest = 'staff-manifest.json';
            $portalAppTitle = 'Staff Workspace';
            $portalThemeColor = '#11151e';
        } elseif (request()->is('admin*')) {
            $portalManifest = 'admin-manifest.json';
            $portalAppTitle = 'Admin Portal';
            $portalThemeColor = '#1e3c72';
        }
    @endphp
    <link rel="manifest" href="{{ asset($portalManifest) }}">
    <meta name="theme-color" content="{{ $portalThemeColor }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $portalAppTitle }}">

    <!------------------------------------------
      Main CSS File
    <------------------------------------------>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/bootstrap.min.css') }}">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="{{ asset('/backend/assets/css/all.min.css') }}" />
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/responsive.css') }}">
    <!-- toastr alert -->
    <link rel="stylesheet" href="{{asset('notification_assets/css/toastr.min.css')}}" />
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Julius+Sans+One" rel="stylesheet">
    @yield('page-css')
    {!! $insertHeaderFooter?$insertHeaderFooter->header:'' !!}

</head>
