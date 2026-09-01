<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.theme1.auth-client.layouts.head')
<body id="body">
@include('components.impersonation-bar')

@include('frontend.theme1.auth-client.layouts.pre-loader')

<!-- Start Page Wrapper  -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="page-wrapper">
    @include('frontend.theme1.auth-client.menus.header-menu')

    <!-- Inner Section Start -->
    @if(!request()->is('client/*'))
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

        <!-- Start Client Dashboard Section-->
        <section class="client-dashboard-section pt-4 pb-5" style="background-color: #0e1117 !important; min-height: 90vh;">
            <div class="container-fluid px-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="client-dashboard-area style-2" style="background: transparent; border: none; box-shadow: none;">
                            @include('frontend.theme1.auth-client.menus.left-bar')

                            <div class="main" style="background: #11151e; border: 1px solid #28303f; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                                <div class="top-bar-wrapper mb-3" style="border-bottom: 1px solid #28303f; padding-bottom: 15px;">
                                    <div class="topbar">
                                        <div class="toggle text-warning" id="clientDashboardMenuBtn"><i class="fas fa-bars fa-lg"></i></div>
                                    </div>
                                    <div class="user-avatar d-flex align-items-center">
                                        <div class="text-right mr-3 d-none d-md-block">
                                            <strong class="text-white d-block" style="font-size: 13px;">{{ Auth::user()->name }}</strong>
                                            <small class="text-warning font-weight-bold">CLI-{{ sprintf('%05d', Auth::user()->id) }}</small>
                                        </div>
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()?->profile_photo_path)
                                            <img class="rounded-circle img-thumbnail" width="45px" height="45px" style="object-fit: cover; border-color: #fecc56;" src="{{ Storage::url(Auth::user()?->profile_photo_path) }}"
                                                 alt="{{ Auth::user()->name }}"/>
                                        @else
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 16px; font-weight: bold; border: 2px solid #fecc56;">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Client Dashboard Section -->


    @include('frontend.theme1.auth-client.layouts.footer')
</div>
<!-- End Page Wrapper  -->



@include('frontend.theme1.auth-client.layouts.script')

@include('backend.layouts.toster-script')
{!! $insertHeaderFooter?$insertHeaderFooter->footer:'' !!}
@include('components.chatwoot-widget')
</body>

</html>
