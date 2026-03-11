<!DOCTYPE html>
<html>
<head>
    <title>bdCoder - Account - Login</title>
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
    <!-- FAVICON  -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset($logoFavicon?$logoFavicon->favicon:'') }}" />
    <!-- BOOTSTRAP - V 4.0.0 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/dist/css/bootstrap.min.css') }}" />
    <!-- MATERIAL ICONS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/material-icons/material-icons.css') }}" />
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/font-awesome/font-awesome.min.css') }}" />
    <!-- WEATHER ICONS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/weather-icons/css/weather-icons.min.css') }}" />
    <!-- FLAG ICON CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/icons/flag-icon-css/css/flag-icon.min.css') }}" />
    <!-- OVERLAYSCROLLBARS -->
    <link type="text/css" href="{{ asset('backend/assets/plugin/OverlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet"/>
    <!-- JVECTORMAP -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/jVectormap/jquery-jvectormap-2.0.3.css') }}" />
    <!-- Circliful Master -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/circliful/css/jquery.circliful.css') }}" />
    <!-- DATA TABLES -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/DataTables/1.10.16/css/dataTables.bootstrap4.min.css') }}" />
    <!-- SUMMERNOTE -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/summernote/summernote-bs4.css') }}" />
    <!-- JQUERY NOTIFY -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/notify/css/notify.css') }}" />
    <!-- BOOTSTRAP SLIDER -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/bootstrap-slider/bootstrap-slider.min.css') }}" />
    <!-- SUMOSELECT -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugin/sumoselect/sumoselect.min.css') }}" />
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Julius+Sans+One" rel="stylesheet">
    <!-- STYLE -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/login-custom.css') }}" />
</head>

<body>
<!------------------------------------------------------------------------------------------------>
<!-- WRAPPER ------------------------------------------------------------------------------------->
<div id="wrapper" class=" wrapper-left-fixed wrapper-header-fixed bg-dark">
    <div class="d-sm-flex justify-content-sm-center d-block container">
        @yield('content')
    </div>
</div>
<!-- END WRAPPER --------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------>
<!-- JAVASCRIPT ---------------------------------------------------------------------------------->
<!-- JQUERY -->
<script src="{{ asset('backend/assets/libraries/jquery/jquery.min.js') }}"></script>
<!-- JQUERY UI -->
<script src="{{ asset('backend/assets/libraries/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- POPPER -->
<script src="{{ asset('backend/assets/libraries/popper/popper.min.js') }}"></script>
<!-- BOOTSTRAP - V 4.0.0 -->
<script src="{{ asset('backend/assets/dist/js/bootstrap.min.js') }}"></script>
<!-- CHARTJS -->
<script src="{{ asset('backend/assets/plugin/chartJs/Chart.bundle.min.js') }}"></script>
<!-- CIRCLIFUL MASTER -->
<script src="{{ asset('backend/assets/plugin/circliful/js/jquery.circliful.js') }}"></script>
<!-- OVERLAYSCROLLBARS -->
<script src="{{ asset('backend/assets/plugin/OverlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- SUMMERNOTE -->
<script src="{{ asset('backend/assets/plugin/summernote/summernote-bs4.js') }}"></script>
<!-- JQUERY-NOTIFY -->
<script src="{{ asset('backend/assets/plugin/notify/js/notify.js') }}"></script>
<!-- JVECTORMAP -->
<script src="{{ asset('backend/assets/plugin/jVectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugin/jVectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('backend/assets/plugin/jVectormap/jquery-jvectormap-us-aea.js') }}"></script>
<script src="{{ asset('backend/assets/plugin/jVectormap/jquery-jvectormap-ca-lcc.js') }}"></script>
<script src="{{ asset('backend/assets/plugin/jVectormap/jquery-jvectormap-fr-regions-mill.js') }}"></script>
<!-- BOOTSTRAP SLIDER -->
<script src="{{ asset('backend/assets/plugin/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
<!-- TINY COLOR PICKER -->
<script src="{{ asset('backend/assets/plugin/tinyColorPicker/jqColorPicker.min.js') }}"></script>
<!-- SUMOSELECT -->
<script src="{{ asset('backend/assets/plugin/sumoselect/jquery.sumoselect.min.js') }}" ></script>
<!-- INPUTMASK -->
<script src="{{ asset('backend/assets/libraries/jquery-inputmask/jquery.inputmask.bundle.js') }}" ></script>
<!-- PLUGINS -->
<script src="{{ asset('backend/assets/js/plugin.js') }}"></script>
<!-- FUNCTIONS -->
<script src="{{ asset('backend/assets/js/functions.js') }}"></script>
<!-- END JAVASCRIPT ------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------>
<script>
    $(document).ready(function () {
        $(document).on("click", ".login-credential-copy-action", function () {
            var user = $(this).data('user');
            var pass = user == 'user'?1234:123;
            $("#inputPseudo").val(user + '@demo.com');
            $("#inputPassword").val(user +pass);
        });

        var appDemo = {{env('APP_DEMO', false)}};
        if(appDemo){
            $(".box-account").css('width', '385px');
        }
    });
</script>
</body>
</html>

