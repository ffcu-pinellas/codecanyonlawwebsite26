<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.theme1.layouts.head')

<body id="body">


@include('frontend.theme1.layouts.pre-loader')

<!-- Start Page Wrapper  -->
<div class="page-wrapper">

    @include('frontend.theme1.menus.header-menu')

    @yield('content')

    @include('frontend.theme1.layouts.footer')
</div>
<!-- End Page Wrapper  -->



@include('frontend.theme1.layouts.script')
@yield('page-script')
@include('backend.layouts.toster-script')
{!! $insertHeaderFooter?$insertHeaderFooter->footer:'' !!}
</body>

</html>
