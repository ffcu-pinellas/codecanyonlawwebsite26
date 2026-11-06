<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark-mode">

@include('frontend.theme1.auth-client.layouts.head')

<body id="body" class="dark-mode" style="background-color: #0a0c10 !important; color: #f1f5f9; min-height: 100vh; overflow-x: hidden;">
@include('components.impersonation-bar')
@include('frontend.theme1.auth-client.layouts.pre-loader')

<!-- Google Translate Container -->
<div id="google_translate_element" style="display:none;"></div>

<!-- Dedicated Executive Staff Navigation Header -->
<header class="client-portal-navbar py-2 px-3 px-lg-4" style="background: #11151e; border-bottom: 1px solid #28303f; position: sticky; top: 0; z-index: 1020; box-shadow: 0 4px 16px rgba(0,0,0,0.4);">
    <div class="container-fluid px-0 d-flex justify-content-between align-items-center">
        <!-- Brand & Mobile Toggle -->
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-dark text-warning p-2 mr-3 d-lg-none" id="staffMobileToggleBtn" style="border: 1px solid #28303f; background: #161a23;">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            <a href="{{ route('staff.dashboard') }}" class="d-flex align-items-center text-decoration-none">
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
        <div class="d-none d-xl-flex align-items-center">
            <span class="badge px-3 py-2" style="background: rgba(254, 204, 86, 0.1); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 11.5px; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 700;">
                <i class="fas fa-user-tie mr-1"></i> {{ __('Staff & Practitioner Workspace') }} &bull; {{ config('app.name', 'Your CPA Expert') }}
            </span>
        </div>

        <!-- Right Header Utility Controls (Dark/Light, Currency, Language, Profile) -->
        <div class="d-flex align-items-center" style="gap: 8px;">

            <!-- Dark / Light Mode Switcher -->
            <button type="button" id="themeModeToggle" class="btn btn-sm btn-outline-secondary font-weight-bold d-flex align-items-center" onclick="toggleThemeMode()" title="Toggle Dark / Light Mode" style="border-radius: 20px; padding: 4px 10px; font-size: 11.5px; border-color: #3b4252; color: #cbd5e1; background: #161a23;">
                <i class="fas fa-sun text-warning" id="themeModeIcon"></i>
                <span id="themeModeText" class="d-none d-sm-inline ml-1">Light Mode</span>
            </button>

            <!-- Language Switcher Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-dark dropdown-toggle font-weight-bold text-light px-2 py-1" type="button" id="languageDropdown" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #161a23; border: 1px solid #28303f; border-radius: 6px; font-size: 11.5px;">
                    <span id="currentLangFlag">🇺🇸</span> <span id="currentLangShort" class="d-none d-sm-inline">EN</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow-lg p-2" aria-labelledby="languageDropdown" style="background: #161a23; border: 1px solid #28303f; min-width: 170px; max-height: 280px; overflow-y: auto;">
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('en', 'English', '🇺🇸')" style="font-size: 12px;">
                        <span class="mr-2">🇺🇸</span> <span>English</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('es', 'Español', '🇪🇸')" style="font-size: 12px;">
                        <span class="mr-2">🇪🇸</span> <span>Español</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('fr', 'Français', '🇫🇷')" style="font-size: 12px;">
                        <span class="mr-2">🇫🇷</span> <span>Français</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('de', 'Deutsch', '🇩🇪')" style="font-size: 12px;">
                        <span class="mr-2">🇩🇪</span> <span>Deutsch</span>
                    </a>
                </div>
            </div>

            <!-- Staff Profile Avatar Dropdown -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center p-1 text-decoration-none" href="#" id="staffUserMenuDropdown" role="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()?->profile_photo_path)
                        <img class="rounded-circle mr-2" width="34" height="34" src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" style="border: 2px solid #fecc56; object-fit: cover;"/>
                    @else
                        <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center text-dark font-weight-bold" style="width: 34px; height: 34px; background: #fecc56; font-size: 13px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-none d-md-block text-left" style="max-width: 130px;">
                        <strong class="text-white d-block small mb-0" style="line-height: 1.2;">{{ Auth::user()->name }}</strong>
                        <small class="text-muted" style="font-size: 10px;">ID: {{ Auth::user()->staffDetail?->staff_id ?: 'Staff' }}</small>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-lg p-2" aria-labelledby="staffUserMenuDropdown" style="background: #161a23; border: 1px solid #28303f; min-width: 220px;">
                    <div class="px-3 py-2 border-bottom mb-2" style="border-color: #28303f !important;">
                        <span class="d-block font-weight-bold text-white small">{{ Auth::user()->name }}</span>
                        <small class="text-muted d-block text-truncate">{{ Auth::user()->email }}</small>
                    </div>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('staff.dashboard') }}" style="font-size: 13px;">
                        <i class="fas fa-tachometer-alt text-warning mr-2"></i> {{ __('Staff Dashboard') }}
                    </a>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('staff.tasks.index') }}" style="font-size: 13px;">
                        <i class="fas fa-tasks text-warning mr-2"></i> {{ __('Assigned Tasks') }}
                    </a>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('staff.financial-ledger') }}" style="font-size: 13px;">
                        <i class="fas fa-wallet text-warning mr-2"></i> {{ __('Financial Ledger') }}
                    </a>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('staff.payment-method') }}" style="font-size: 13px;">
                        <i class="fas fa-university text-warning mr-2"></i> {{ __('Direct Deposit Setup') }}
                    </a>
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('staff.messages') }}" style="font-size: 13px;">
                        <i class="fas fa-comments text-warning mr-2"></i> {{ __('Firm Counsel Chat') }}
                    </a>
                    <div class="dropdown-divider" style="border-color: #28303f !important;"></div>
                    <form action="{{ route('logout') }}" method="POST" id="staffTopNavLogoutForm">
                        @csrf
                        <a class="dropdown-item text-danger py-2 rounded font-weight-bold" href="javascript:void(0)" onclick="document.getElementById('staffTopNavLogoutForm').submit();" style="font-size: 13px;">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Sign Out') }}
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Main Staff Application Canvas -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="staff-portal-wrapper" style="background-color: #0a0c10; min-height: calc(100vh - 60px); display: flex; flex-direction: row; align-items: flex-start;">
    <!-- Sidebar Column -->
    <div class="staff-sidebar-col d-none d-lg-block" id="staffSidebarCol" style="width: 250px; flex-shrink: 0; padding: 20px 0 20px 16px;">
        @include('frontend.theme1.auth-staff.menus.left-bar')
    </div>

    <!-- Content Column -->
    <div class="staff-content-col" style="flex: 1; min-width: 0; padding: 20px 16px 20px 16px;">
        <div class="staff-content-container p-3 p-md-4 rounded" style="background: #11151e; border: 1px solid #28303f; box-shadow: 0 4px 20px rgba(0,0,0,0.3); min-height: 80vh;">
            
            <!-- Mobile PWA Install Notification -->
            <div id="pwaInstallBanner" class="alert alert-info d-none mb-3 shadow-sm border-0 d-flex flex-wrap align-items-center justify-content-between" style="border-radius: 10px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff;">
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
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(34,197,94,0.15); border-color: rgba(34,197,94,0.3); color: #4ade80;">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #f87171;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Minimal Staff Encrypted App Footer -->
<footer class="py-3 px-4 border-top text-center" style="background: #0d1017; border-color: #28303f !important; color: #94a3b8; font-size: 12px;">
    <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center">
        <div class="text-white">
            &copy; {{ date('Y') }} {{ config('app.name', 'Your CPA Expert') }}. {{ __('All rights reserved. Privileged Staff & Practitioner Portal.') }}
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); padding: 5px 10px;">
                <i class="fas fa-lock mr-1"></i> {{ __('256-Bit SSL Encrypted Session') }}
            </span>
        </div>
    </div>
</footer>

@include('frontend.theme1.auth-client.layouts.script')
@include('backend.layouts.toster-script')
@yield('modal')
@yield('page-script')

<style>
    /* ===== MODERN DARK THEME FOR STAFF WORKSPACE ===== */
    html, body {
        font-family: 'Montserrat', sans-serif !important;
        background-color: #0a0c10 !important;
        color: #f1f5f9 !important;
    }
    .staff-portal-wrapper { background-color: #0a0c10 !important; }
    h1, h2, h3, h4, h5, h6, .card-title { color: #f1f5f9 !important; }
    strong, b { color: inherit !important; }
    p, span, li { color: #cbd5e1; }
    label, small { color: #94a3b8; }
    .text-muted { color: #64748b !important; }
    .text-white { color: #f1f5f9 !important; }
    .text-dark { color: #f1f5f9 !important; }
    .text-warning { color: #fecc56 !important; }
    .text-success { color: #4ade80 !important; }
    .text-danger { color: #f87171 !important; }
    .text-info { color: #38bdf8 !important; }
    .text-primary { color: #60a5fa !important; }
    
    /* Staff Sidebar Navigation Dark Styling */
    .client-navigation {
        background: #11151e !important;
        border: 1px solid #28303f !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3) !important;
    }
    .client-navigation ul li a {
        color: #94a3b8 !important;
        padding: 12px 18px !important;
        border-radius: 8px !important;
        margin: 4px 8px !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center !important;
    }
    .client-navigation ul li a .icon {
        margin-right: 12px !important;
        width: 22px !important;
        text-align: center !important;
        color: #64748b !important;
    }
    .client-navigation ul li:hover a,
    .client-navigation ul li.active a {
        background: rgba(254, 204, 86, 0.12) !important;
        color: #fecc56 !important;
    }
    .client-navigation ul li:hover a .icon,
    .client-navigation ul li.active a .icon {
        color: #fecc56 !important;
    }

    /* Cards & containers dark */
    .card, .dashboard-card, .ledger-card {
        background: #161a23 !important;
        border: 1px solid #28303f !important;
        color: #f1f5f9 !important;
    }
    .card-header { background: #1f2533 !important; border-color: #28303f !important; color: #f1f5f9 !important; }
    .card-body { background: inherit !important; color: #f1f5f9 !important; }
    
    /* Tables dark */
    table { color: #f1f5f9 !important; }
    thead th { color: #94a3b8 !important; background: #1f2533 !important; border-color: #28303f !important; }
    tbody tr { border-color: #28303f !important; }
    tbody td { color: #e2e8f0 !important; border-color: #28303f !important; background: transparent !important; }
    tbody tr:hover td { background: rgba(255,255,255,0.04) !important; }
    
    /* Forms dark */
    .form-control, .form-select, select, input, textarea {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    .form-control:focus { border-color: #fecc56 !important; box-shadow: 0 0 0 2px rgba(254,204,86,0.2) !important; color: #f1f5f9 !important; }
    input::placeholder, textarea::placeholder { color: #475569 !important; }
    
    /* Mobile responsive sidebar */
    @media (max-width: 991px) {
        .staff-sidebar-col {
            position: fixed;
            top: 0;
            left: -270px;
            width: 260px !important;
            height: 100vh;
            z-index: 1090;
            transition: left 0.3s ease;
            background: #11151e;
            padding: 20px 10px !important;
        }
        .staff-sidebar-col.show {
            left: 0 !important;
            display: block !important;
        }
        .sidebar-overlay.active {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.6);
            z-index: 1080;
        }
    }
</style>

<script>
    // Mobile Sidebar Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('staffMobileToggleBtn');
        const sidebar = document.getElementById('staffSidebarCol');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('mobileCloseSidebar');

        if (toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('active');
            });
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('active');
            });
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('active');
                });
            }
        }
    });

    // PWA Service Worker Registration & Install Prompt
    let deferredPwaPrompt = null;
    const pwaBanner = document.getElementById('pwaInstallBanner');
    const pwaInstallBtn = document.getElementById('pwaInstallBtn');

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Staff PWA SW registered:', reg.scope))
                .catch(err => console.warn('Staff PWA SW error:', err));
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
