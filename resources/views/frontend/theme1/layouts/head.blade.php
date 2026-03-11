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

    <!-- Favicon -->
    <link href="{{ $logoFavicon?$logoFavicon->favicon:'' }}" rel="shortcut icon" type="image/png">

    <!------------------------------------------
      Main CSS File
    <------------------------------------------>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/bootstrap.min.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/responsive.css') }}">

    @yield('page-css')
    {!! $insertHeaderFooter?$insertHeaderFooter->header:'' !!}

</head>

