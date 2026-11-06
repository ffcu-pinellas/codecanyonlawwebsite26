<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.theme1.auth-client.layouts.head')
<style>
    .client-dashboard-section {
        margin-top: 130px !important;
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
    @media (min-width: 768px) {
        .sidebar-overlay, .sidebar-overlay.active {
            display: none !important;
        }
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
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()?->profile_photo_path)
                                    <img class="rounded-circle py-1 img-thumbnail" width="55px" src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                         alt="{{ Auth::user()->name }}"/>
                                @else
                                    <img src="{{ Auth::user()?->gender == 'male' ? asset('backend/assets/img/profile/male.jpg'):(Auth::user()?->gender == 'female' ? asset('backend/assets/img/profile/female.jpg'):asset('backend/assets/img/profile/other.png'))  }}"
                                        class="rounded-circle py-1 img-thumbnail" width="55px">
                                @endif
                            </div>
                        </div>
                        
                        <!-- Mobile PWA Install Notification / Tooltip Banner -->
                        <div id="pwaInstallBanner" class="alert alert-info d-none mx-3 my-2 shadow-sm border-0 d-flex flex-wrap align-items-center justify-content-between" style="border-radius: 10px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff;">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="mr-3" style="font-size: 1.8rem;">📲</div>
                                <div>
                                    <strong class="d-block" style="font-size: 14px;">Install Staff Workspace App</strong>
                                    <small style="opacity: 0.9;">Access tasks, client chats, and scan receipts instantly from your mobile home screen.</small>
                                </div>
                            </div>
                            <div>
                                <button type="button" id="pwaInstallBtn" class="btn btn-warning btn-sm font-weight-bold mr-2 text-dark px-3 shadow-sm">
                                    <i class="fas fa-download mr-1"></i> Install App
                                </button>
                                <button type="button" class="btn btn-link text-white btn-sm p-0 ml-2" onclick="dismissPwaBanner()" title="Dismiss">
                                    <i class="fas fa-times"></i>
                                </button>
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
@yield('modal')

<script>
    // PWA Service Worker Registration & Install Prompt
    let deferredPwaPrompt = null;
    const pwaBanner = document.getElementById('pwaInstallBanner');
    const pwaInstallBtn = document.getElementById('pwaInstallBtn');

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Staff PWA Service Worker registered:', reg.scope))
                .catch(err => console.warn('PWA SW registration error:', err));
        });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPwaPrompt = e;
        if (!localStorage.getItem('staff_pwa_banner_dismissed')) {
            if (pwaBanner) pwaBanner.classList.remove('d-none');
        }
    });

    if (pwaInstallBtn) {
        pwaInstallBtn.addEventListener('click', async () => {
            if (!deferredPwaPrompt) return;
            deferredPwaPrompt.prompt();
            const { outcome } = await deferredPwaPrompt.userChoice;
            if (outcome === 'accepted') {
                if (pwaBanner) pwaBanner.classList.add('d-none');
            }
            deferredPwaPrompt = null;
        });
    }

    function dismissPwaBanner() {
        if (pwaBanner) pwaBanner.classList.add('d-none');
        localStorage.setItem('staff_pwa_banner_dismissed', 'true');
    }
</script>
</body>
</html>
