<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ENCODING -->
    <meta charset="UTF-8" />
    <!-- AUTHOR -->
    <meta name="author" content="bdCoder" />
    <!-- DESCRIPTION -->
    <meta name="description" content="Modern Bootstrap 4 Admin Template - Fully Responsive" />
    <!-- IE EDGE COMPATIBILITY -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- RESPONSIVE BROWSER TO SCREEN WIDTH -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    <!------------------------------------------------------------------------------------------------>
    <!-- Favicon -->
    <link href="{{ $logoFavicon?$logoFavicon->favicon:'' }}" rel="shortcut icon" type="image/png">
    <!-- BOOTSTRAP - V 4.0.0 -->
    <link rel="stylesheet" href="{{asset('/backend/assets/dist/css/bootstrap.min.css')}}" />
    <!-- MATERIAL ICONS -->
    <link rel="stylesheet" href="{{asset('/backend/assets/icons/material-icons/material-icons.css')}}" />
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="{{ asset('/backend/assets/css/all.min.css') }}" />
    <!-- WEATHER ICONS -->
    <link rel="stylesheet" href="{{asset('/backend/assets/icons/weather-icons/css/weather-icons.min.css')}}" />
    <!-- FLAG ICON CSS -->
    <link rel="stylesheet" href="{{asset('/backend/assets/icons/flag-icon-css/css/flag-icon.min.css')}}" />
    <!-- OVERLAYSCROLLBARS -->
    <link type="text/css" href="{{asset('/backend/assets/plugin/OverlayScrollbars/css/OverlayScrollbars.min.css')}}" rel="stylesheet"/>
    <!-- JVECTORMAP -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/jVectormap/jquery-jvectormap-2.0.3.css')}}" />
    <!-- Circliful Master -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/circliful/css/jquery.circliful.css')}}" />
    <!-- DATA TABLES -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/DataTables/1.10.16/css/dataTables.bootstrap4.min.css')}}" />
    <!-- SUMMERNOTE -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/summernote/summernote-bs4.css')}}" />
    <!-- JQUERY NOTIFY -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/notify/css/notify.css')}}" />
    <!-- BOOTSTRAP SLIDER -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/bootstrap-slider/bootstrap-slider.min.css')}}" />
    <!-- SUMOSELECT -->
    <link rel="stylesheet" href="{{asset('/backend/assets/plugin/sumoselect/sumoselect.min.css')}}" />
    <!-- IMAGE UPLOADER -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/image-uploader.min.css')}}" />
    <!-- TAG INPUT -->
    <link rel="stylesheet" href="{{asset('backend/assets/plugin/tag-input/tagsinput.css')}}" />
    <!-- toastr alert -->
    <link rel="stylesheet" href="{{asset('notification_assets/css/toastr.min.css')}}" />
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Julius+Sans+One" rel="stylesheet">
    <!-- STYLE -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}" />

    @yield('page-css')

</head>
