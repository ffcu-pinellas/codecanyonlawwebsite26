<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.theme1.auth-client.layouts.head')
<style>
    .client-dashboard-section {
        margin-top: 130px !important;
        position: relative;
        z-index: 10;
    }
    .client-navigation {
        background-color: #1a252f !important;
        z-index: 1050 !important;
    }
    .client-navigation ul li.active a {
        background-color: #34495e !important;
        color: #fff !important;
    }
    .top-bar-wrapper {
        z-index: 5 !important;
    }
</style>
<body id="body">

@include('frontend.theme1.auth-client.layouts.pre-loader')

<!-- Start Page Wrapper  -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="page-wrapper">
    @include('frontend.theme1.auth-client.menus.header-menu')

    <!-- Inner Section Start -->
    @if(!request()->is('staff/*'))
        <section class="inner-area" style="@if(!empty($pageContent->bg_img))background-image: url({{asset($pageContent->bg_img)}});@else background-image: url({{asset('frontend/theme1/images/bg/2.jpg')}}); @endif">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>{{isset($title)?clean($title):''}}</h4>
                        <p><a href="{{route('home')}}">{{__('Home')}}</a> {{$title?clean($title):''}}</p>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Inner Section End -->

    <!-- Start Staff Section-->
    <section class="client-dashboard-section pt-5 pb-5 bg-light">
        <div class="container">
            <div class="col-lg-12">
                <div class="client-dashboard-area style-2">
                    @include('frontend.theme1.auth-staff.menus.left-bar')

                    <div class="main">
                        <div class="top-bar-wrapper">
                            <div class="topbar">
                                <div class="toggle" id="clientDashboardMenuBtn"></div>
                            </div>
                            <div class="user-avatar d-flex align-items-center">
                                <span class="mr-3 font-weight-bold text-dark d-none d-sm-inline">{{ Auth::user()->name }}</span>
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <img class="rounded-circle py-1 img-thumbnail" width="55px" src="{{ Storage::url(Auth::user()?->profile_photo_path) }}"
                                         alt="{{ Auth::user()->name }}"/>
                                @else
                                    <img src="{{ Auth::user()->gender == 'male' ? asset('backend/assets/img/profile/male.jpg'):(Auth::user()->gender == 'female' ? asset('backend/assets/img/profile/female.jpg'):asset('backend/assets/img/profile/other.png'))  }}"
                                        class="rounded-circle py-1 img-thumbnail" width="55px">
                                @endif
                            </div>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Staff Section -->

    @include('frontend.theme1.auth-client.layouts.footer')
</div>
<!-- End Page Wrapper  -->

@include('frontend.theme1.auth-client.layouts.script')
@include('backend.layouts.toster-script')
{!! $insertHeaderFooter?$insertHeaderFooter->footer:'' !!}
</body>
</html>
