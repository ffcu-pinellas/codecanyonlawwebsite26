<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.theme1.auth-client.layouts.head')
<body id="body" style="background-color: #0a0c10 !important; color: #f1f5f9; min-height: 100vh; overflow-x: hidden;">
@include('components.impersonation-bar')

@include('frontend.theme1.auth-client.layouts.pre-loader')

<!-- Dedicated Executive App Navigation Header -->
<header class="client-portal-navbar py-2 px-3 px-lg-4" style="background: #11151e; border-bottom: 1px solid #28303f; position: sticky; top: 0; z-index: 1020; box-shadow: 0 4px 16px rgba(0,0,0,0.4);">
    <div class="container-fluid px-0 d-flex justify-content-between align-items-center">
        <!-- Brand & Toggle -->
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-dark text-warning p-2 mr-3 d-lg-none" id="clientDashboardMenuBtn" style="border: 1px solid #28303f; background: #161a23;">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            <a href="{{ route('client.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                @if(!empty($logoFavicon) && !empty($logoFavicon->logo))
                    <img src="{{ asset($logoFavicon->logo) }}" alt="{{ config('app.name') }}" style="max-height: 38px; width: auto;" class="mr-2">
                @else
                    <span class="font-weight-bold text-white" style="font-size: 1.15rem; letter-spacing: 0.5px;">
                        <span class="text-warning"><i class="fas fa-balance-scale mr-1"></i></span> {{ config('app.name', 'Your CPA Expert') }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Center Status Badge -->
        <div class="d-none d-md-flex align-items-center">
            <span class="badge px-3 py-2" style="background: rgba(254, 204, 86, 0.1); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 11.5px; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 700;">
                <i class="fas fa-shield-alt mr-1"></i> {{ __('Privileged Legal & CPA File') }} &bull; CLI-{{ sprintf('%05d', Auth::user()->id) }}
            </span>
        </div>

        <!-- Right User & Counsel Area -->
        <div class="d-flex align-items-center" style="gap: 12px;">
            <!-- Live Support Link -->
            <a href="{{ route('client.conversation.index') }}" class="btn btn-sm btn-dark text-warning d-none d-sm-inline-flex align-items-center font-weight-bold" style="background: #161a23; border: 1px solid rgba(254,204,86,0.3); border-radius: 6px; padding: 6px 12px; font-size: 12px;">
                <i class="fas fa-comment-dots mr-1"></i> {{ __('Live Counsel Support') }}
            </a>

            <!-- User Avatar & Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white" id="userMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover; border: 2px solid #fecc56;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 14px; font-weight: bold; border: 2px solid #fecc56;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-none d-xl-block text-left ml-2">
                        <strong class="text-white d-block small mb-0" style="line-height: 1.2;">{{ Auth::user()->name }}</strong>
                        <small class="text-warning font-weight-bold" style="font-size: 10px;">CLI-{{ sprintf('%05d', Auth::user()->id) }}</small>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-lg p-2" aria-labelledby="userMenuDropdown" style="background: #161a23; border: 1px solid #28303f; min-width: 220px;">
                    <div class="px-3 py-2 border-bottom mb-2" style="border-color: #28303f !important;">
                        <span class="d-block font-weight-bold text-white small">{{ Auth::user()->name }}</span>
                        <small class="text-muted d-block text-truncate">{{ Auth::user()->email }}</small>
                    </div>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('client.profile') }}" style="font-size: 13px;">
                        <i class="fas fa-user-shield text-warning mr-2"></i> {{ __('Security & PIN Settings') }}
                    </a>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('client.kyc.index') }}" style="font-size: 13px;">
                        <i class="fas fa-file-upload text-warning mr-2"></i> {{ __('Upload Documents') }}
                    </a>
                    <div class="dropdown-divider" style="border-color: #28303f !important;"></div>
                    <form action="{{ route('logout') }}" method="POST" id="topNavLogoutForm">
                        @csrf
                        <a class="dropdown-item text-danger py-2 rounded font-weight-bold" href="javascript:void(0)" onclick="document.getElementById('topNavLogoutForm').submit();" style="font-size: 13px;">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Sign Out') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Client Application Canvas -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<main class="client-portal-wrapper py-4" style="background-color: #0a0c10; min-height: calc(100vh - 120px);">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row">
            <!-- Sidebar Column -->
            <div class="col-lg-3 col-xl-2 mb-4 mb-lg-0">
                @include('frontend.theme1.auth-client.menus.left-bar')
            </div>

            <!-- Content Column -->
            <div class="col-lg-9 col-xl-10">
                <div class="client-content-container p-3 p-md-4 rounded" style="background: #11151e; border: 1px solid #28303f; box-shadow: 0 4px 20px rgba(0,0,0,0.3); min-height: 80vh;">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Minimal App Footer -->
<footer class="py-3 px-4 border-top text-center" style="background: #0d1017; border-color: #28303f !important; color: #64748b; font-size: 12px;">
    <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center">
        <div>
            &copy; {{ date('Y') }} {{ config('app.name', 'Your CPA Expert') }}. {{ __('All rights reserved. Privileged & Confidential Legal File.') }}
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge" style="background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.25);">
                <i class="fas fa-lock mr-1"></i> {{ __('256-Bit SSL Encrypted Session') }}
            </span>
        </div>
    </div>
</footer>

@include('frontend.theme1.auth-client.layouts.script')
@include('backend.layouts.toster-script')
@include('components.chatwoot-widget')

<style>
    /* Executive Client App Overrides */
    body {
        font-family: 'Montserrat', sans-serif !important;
        background-color: #0a0c10 !important;
    }
    .dropdown-item:hover {
        background-color: #1f2533 !important;
        color: #ffffff !important;
    }
    .sidebar-overlay {
        display: none;
    }
    .sidebar-overlay.active {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.75);
        backdrop-filter: blur(2px);
        z-index: 1050;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('clientDashboardMenuBtn');
        const sidebar = document.getElementById('ifwClientSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('mobileCloseSidebar');

        function openSidebar() {
            if (sidebar) sidebar.classList.add('active');
            if (overlay) overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openSidebar();
            });
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }
    });
</script>

</body>
</html>
