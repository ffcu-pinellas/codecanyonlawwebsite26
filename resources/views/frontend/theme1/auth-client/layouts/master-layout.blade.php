<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.theme1.auth-client.layouts.head')
<body id="body">

@include('frontend.theme1.auth-client.layouts.pre-loader')

<!-- Start Page Wrapper  -->
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

        <!-- Start About Section-->
        <section class="client-dashboard-section">
            <div class="container">
                <div class="col-lg-12">
                    <div class="client-dashboard-area style-2">
                        @include('frontend.theme1.auth-client.menus.left-bar')

                        <div class="main">
                            <div class="top-bar-wrapper">
                                <div class="topbar">
                                    <div class="toggle" id="clientDashboardMenuBtn"></div>
                                </div>
                                <div class="user-avatar">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())

                                        <img class="rounded-circle py-1 img-thumbnail" width="55px" src="{{ Storage::url(Auth::user()?->profile_photo_path) }}"
                                             alt="{{ Auth::user()->name }}"/>
                                    @else
                                        <img
                                            src="{{ Auth::user()->gender == 'male' ? asset('backend/assets/img/profile/male.jpg'):(Auth::user()->gender == 'female' ? asset('backend/assets/img/profile/female.jpg'):asset('backend/assets/img/profile/other.png'))  }}"
                                            class="rounded-circle py-1 img-thumbnail" width="55px">
                                    @endif
                                </div>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End About Section -->


    @include('frontend.theme1.auth-client.layouts.footer')
</div>
<!-- End Page Wrapper  -->



@include('frontend.theme1.auth-client.layouts.script')

@include('backend.layouts.toster-script')
{!! $insertHeaderFooter?$insertHeaderFooter->footer:'' !!}
</body>

</html>
