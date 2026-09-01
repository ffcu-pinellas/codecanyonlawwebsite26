<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark-mode">

@include('frontend.theme1.auth-client.layouts.head')
<body id="body" class="dark-mode" style="background-color: #0a0c10 !important; color: #f1f5f9; min-height: 100vh; overflow-x: hidden;">
@include('components.impersonation-bar')
@include('frontend.theme1.auth-client.layouts.pre-loader')

<!-- Google Translate Container (Hidden Engine) -->
<div id="google_translate_element" style="display:none;"></div>

<!-- Dedicated Executive App Navigation Header (IFW Replica) -->
<header class="client-portal-navbar py-2 px-3 px-lg-4" style="background: #11151e; border-bottom: 1px solid #28303f; position: sticky; top: 0; z-index: 1020; box-shadow: 0 4px 16px rgba(0,0,0,0.4);">
    <div class="container-fluid px-0 d-flex justify-content-between align-items-center">
        <!-- Brand & Mobile Toggle -->
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
        <div class="d-none d-xl-flex align-items-center">
            <span class="badge px-3 py-2" style="background: rgba(254, 204, 86, 0.1); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 11.5px; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 700;">
                <i class="fas fa-shield-alt mr-1"></i> {{ __('Secure Client Portal') }} &bull; {{ config('app.name', 'Your CPA Expert') }}
            </span>
        </div>

        <!-- Right Header Utility Controls (Dark/Light, Currency, Language, Profile) -->
        <div class="d-flex align-items-center" style="gap: 8px;">

            <!-- 1. Dark / Light Mode Switcher -->
            <button type="button" id="themeModeToggle" class="btn btn-sm btn-outline-secondary font-weight-bold d-flex align-items-center" onclick="toggleThemeMode()" title="Toggle Dark / Light Mode" style="border-radius: 20px; padding: 4px 10px; font-size: 11.5px; border-color: #3b4252; color: #cbd5e1; background: #161a23;">
                <i class="fas fa-sun text-warning" id="themeModeIcon"></i>
                <span id="themeModeText" class="d-none d-sm-inline ml-1">Light Mode</span>
            </button>

            <!-- 2. Currency Switcher Dropdown -->
            @php
                $userCurrency = Auth::user()->preferred_currency ?: 'USD';
                $currencies = [
                    'USD' => ['sym' => '$', 'name' => 'USD ($)'],
                    'EUR' => ['sym' => '€', 'name' => 'EUR (€)'],
                    'GBP' => ['sym' => '£', 'name' => 'GBP (£)'],
                    'CAD' => ['sym' => '$', 'name' => 'CAD ($)'],
                    'AUD' => ['sym' => '$', 'name' => 'AUD ($)'],
                ];
            @endphp
            <div class="dropdown">
                <button class="btn btn-sm btn-dark dropdown-toggle font-weight-bold text-light px-2 py-1" type="button" id="currencyDropdown" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #161a23; border: 1px solid #28303f; border-radius: 6px; font-size: 11.5px;">
                    <i class="fas fa-dollar-sign text-warning mr-1"></i> <span id="currentCurrencyLabel">{{ $userCurrency }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow-lg p-1" aria-labelledby="currencyDropdown" style="background: #161a23; border: 1px solid #28303f; min-width: 120px;">
                    @foreach($currencies as $code => $cData)
                        <a class="dropdown-item py-1 px-2 text-light rounded d-flex justify-content-between align-items-center" href="javascript:void(0);" onclick="changePortalCurrency('{{ $code }}')" style="font-size: 12px;">
                            <span>{{ $code }}</span>
                            <span class="text-warning font-weight-bold">{{ $cData['sym'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 3. Language Switcher Dropdown -->
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
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('it', 'Italiano', '🇮🇹')" style="font-size: 12px;">
                        <span class="mr-2">🇮🇹</span> <span>Italiano</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('pt', 'Português', '🇵🇹')" style="font-size: 12px;">
                        <span class="mr-2">🇵🇹</span> <span>Português</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('ar', 'العربية', '🇸🇦')" style="font-size: 12px;">
                        <span class="mr-2">🇸🇦</span> <span>العربية</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('zh-CN', '中文', '🇨🇳')" style="font-size: 12px;">
                        <span class="mr-2">🇨🇳</span> <span>中文</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 text-light rounded d-flex align-items-center" href="javascript:void(0);" onclick="setPortalLanguage('ru', 'Русский', '🇷🇺')" style="font-size: 12px;">
                        <span class="mr-2">🇷🇺</span> <span>Русский</span>
                    </a>
                </div>
            </div>

            <!-- 4. User Profile Dropdown -->
            <div class="dropdown ml-1">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white" id="userMenuDropdown" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 34px; height: 34px; object-fit: cover; border: 2px solid #fecc56;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 13px; font-weight: bold; border: 2px solid #fecc56;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-none d-lg-block text-left ml-2">
                        <strong class="text-white d-block small mb-0" style="line-height: 1.2;">{{ Auth::user()->name }}</strong>
                        <small class="text-muted" style="font-size: 10px;">{{ Auth::user()->email }}</small>
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
                    <a class="dropdown-item text-light py-2 rounded" href="{{ route('client.kyc.hub') }}" style="font-size: 13px;">
                        <i class="fas fa-id-card text-warning mr-2"></i> {{ __('Identity Verification (KYC)') }}
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
<div class="client-portal-wrapper" style="background-color: #0a0c10; min-height: calc(100vh - 60px); display: flex; flex-direction: row; align-items: flex-start;">
    <!-- Sidebar Column -->
    <div class="client-sidebar-col d-none d-lg-block" style="width: 250px; flex-shrink: 0; padding: 20px 0 20px 16px;">
        @include('frontend.theme1.auth-client.menus.left-bar')
    </div>

    <!-- Content Column -->
    <div class="client-content-col" style="flex: 1; min-width: 0; padding: 20px 16px 20px 16px;">
        <div class="client-content-container p-3 p-md-4 rounded" style="background: #11151e; border: 1px solid #28303f; box-shadow: 0 4px 20px rgba(0,0,0,0.3); min-height: 80vh;">
            @yield('content')
        </div>
    </div>
</div>

<!-- Minimal App Footer -->
<footer class="py-3 px-4 border-top text-center" style="background: #0d1017; border-color: #28303f !important; color: #94a3b8; font-size: 12px;">
    <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center">
        <div class="text-white">
            &copy; {{ date('Y') }} {{ config('app.name', 'Your CPA Expert') }}. {{ __('All rights reserved. Privileged & Confidential Legal File.') }}
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

<style>
    /* ===== DARK MODE BASE (DEFAULT) ===== */
    html, body {
        font-family: 'Montserrat', sans-serif !important;
        background-color: #0a0c10 !important;
        color: #f1f5f9 !important;
    }
    .client-portal-wrapper { background-color: #0a0c10 !important; }
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
    /* Cards & containers dark */
    .card, .vault-card, .client-content-container, .portal-card {
        background: #161a23 !important;
        border-color: #28303f !important;
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
    .form-group label { color: #94a3b8 !important; }
    /* Badges */
    .badge-warning { background: rgba(254,204,86,0.2) !important; color: #fecc56 !important; }
    .badge-success { background: rgba(34,197,94,0.15) !important; color: #4ade80 !important; }
    .badge-danger { background: rgba(239,68,68,0.15) !important; color: #f87171 !important; }
    .badge-info { background: rgba(56,189,248,0.15) !important; color: #38bdf8 !important; }
    .badge-secondary { background: rgba(100,116,139,0.2) !important; color: #94a3b8 !important; }
    /* Alerts */
    .alert { border-radius: 8px !important; }
    .alert-success { background: rgba(34,197,94,0.1) !important; border-color: rgba(34,197,94,0.3) !important; color: #4ade80 !important; }
    .alert-danger { background: rgba(239,68,68,0.1) !important; border-color: rgba(239,68,68,0.3) !important; color: #f87171 !important; }
    .alert-warning { background: rgba(254,204,86,0.1) !important; border-color: rgba(254,204,86,0.3) !important; color: #fecc56 !important; }
    .alert-info { background: rgba(56,189,248,0.1) !important; border-color: rgba(56,189,248,0.3) !important; color: #38bdf8 !important; }
    /* Buttons */
    .btn-warning { background: #fecc56 !important; color: #000 !important; border-color: #fecc56 !important; }
    .btn-outline-warning { border-color: #fecc56 !important; color: #fecc56 !important; }
    .btn-outline-secondary { border-color: #374151 !important; color: #94a3b8 !important; }
    .btn-secondary { background: #1f2533 !important; border-color: #374151 !important; color: #94a3b8 !important; }
    .btn-dark { background: #161a23 !important; border-color: #28303f !important; color: #f1f5f9 !important; }
    /* Dropdown menus */
    .dropdown-menu { background: #161a23 !important; border-color: #28303f !important; }
    .dropdown-item { color: #e2e8f0 !important; }
    .dropdown-item:hover { background: #1f2533 !important; color: #fecc56 !important; }
    .dropdown-divider { border-color: #28303f !important; }
    /* Footer */
    footer { background: #0d1017 !important; border-color: #28303f !important; color: #64748b !important; }

    /* ===== LIGHT MODE OVERRIDES ===== */
    html.light-mode, body.light-mode {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }
    body.light-mode .client-portal-wrapper { background-color: #f1f5f9 !important; }
    body.light-mode h1, body.light-mode h2, body.light-mode h3,
    body.light-mode h4, body.light-mode h5, body.light-mode h6,
    body.light-mode .card-title { color: #0f172a !important; }
    body.light-mode p, body.light-mode span:not(.badge):not(.text-warning):not(.text-success):not(.text-danger):not(.text-info),
    body.light-mode li, body.light-mode td, body.light-mode th { color: #334155 !important; }
    body.light-mode label, body.light-mode small { color: #64748b !important; }
    body.light-mode .text-muted { color: #94a3b8 !important; }
    body.light-mode .text-white { color: #0f172a !important; }
    body.light-mode .text-dark { color: #0f172a !important; }
    body.light-mode strong, body.light-mode b { color: #0f172a !important; }
    body.light-mode .font-weight-bold { color: #0f172a !important; }
    /* Cards & containers light */
    body.light-mode .card, body.light-mode .vault-card,
    body.light-mode .client-content-container, body.light-mode .portal-card {
        background: #ffffff !important; border-color: #e2e8f0 !important; color: #0f172a !important;
    }
    body.light-mode .card-header { background: #f8fafc !important; border-color: #e2e8f0 !important; color: #0f172a !important; }
    /* Header light */
    body.light-mode header.client-portal-navbar {
        background: #ffffff !important; border-bottom-color: #e2e8f0 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    body.light-mode header.client-portal-navbar .text-white { color: #0f172a !important; }
    body.light-mode header.client-portal-navbar .btn-outline-secondary { border-color: #cbd5e1 !important; color: #475569 !important; }
    body.light-mode header.client-portal-navbar .dropdown-menu { background: #ffffff !important; border-color: #e2e8f0 !important; }
    body.light-mode header.client-portal-navbar .dropdown-item { color: #374151 !important; }
    body.light-mode header.client-portal-navbar .dropdown-item:hover { background: #f1f5f9 !important; color: #0f172a !important; }
    /* Sidebar light */
    body.light-mode .ifw-client-sidebar {
        background: #ffffff !important; border-color: #e2e8f0 !important;
    }
    body.light-mode .ifw-client-sidebar .text-white { color: #0f172a !important; }
    body.light-mode .ifw-client-sidebar .border-bottom { border-color: #e2e8f0 !important; }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a { color: #475569 !important; background: transparent !important; }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a:hover { background: #f1f5f9 !important; color: #0f172a !important; }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li.active > a { background: rgba(254,204,86,0.15) !important; color: #b45309 !important; border-left-color: #fecc56 !important; }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a .nav-icon { color: #b45309 !important; }
    /* Tables light */
    body.light-mode table { color: #334155 !important; }
    body.light-mode thead th { background: #f8fafc !important; color: #475569 !important; border-color: #e2e8f0 !important; }
    body.light-mode tbody td { color: #374151 !important; border-color: #e2e8f0 !important; }
    body.light-mode tbody tr:hover td { background: #f8fafc !important; }
    /* Forms light */
    body.light-mode .form-control, body.light-mode select, body.light-mode input, body.light-mode textarea {
        background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important;
    }
    body.light-mode input::placeholder, body.light-mode textarea::placeholder { color: #94a3b8 !important; }
    body.light-mode .form-group label { color: #475569 !important; }
    /* Buttons light */
    body.light-mode .btn-dark { background: #f1f5f9 !important; border-color: #cbd5e1 !important; color: #374151 !important; }
    body.light-mode .btn-outline-secondary { border-color: #cbd5e1 !important; color: #475569 !important; }
    body.light-mode .btn-secondary { background: #e2e8f0 !important; border-color: #cbd5e1 !important; color: #374151 !important; }
    /* Dropdown light */
    body.light-mode .dropdown-menu { background: #ffffff !important; border-color: #e2e8f0 !important; box-shadow: 0 4px 16px rgba(0,0,0,0.12) !important; }
    body.light-mode .dropdown-item { color: #374151 !important; }
    body.light-mode .dropdown-item:hover { background: #f1f5f9 !important; color: #0f172a !important; }
    /* Footer light */
    body.light-mode footer { background: #ffffff !important; border-color: #e2e8f0 !important; color: #64748b !important; }
    body.light-mode footer .text-white { color: #475569 !important; }
    /* Content wrapper light */
    body.light-mode .client-content-container { background: #ffffff !important; border-color: #e2e8f0 !important; }

    /* Mobile Drawer Overlay */
    .sidebar-overlay { display: none; }
    .sidebar-overlay.active {
        display: block; position: fixed; top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.75); backdrop-filter: blur(3px); z-index: 1080;
    }
    /* Mobile: show sidebar as drawer, stack layout vertically */
    @media (max-width: 991px) {
        html, body { overflow-x: hidden !important; width: 100% !important; max-width: 100vw !important; }
        .client-portal-wrapper { flex-direction: column !important; width: 100% !important; max-width: 100vw !important; overflow-x: hidden !important; }
        .client-sidebar-col { width: 0 !important; padding: 0 !important; margin: 0 !important; display: block !important; }
        .client-content-col { padding: 12px 10px !important; width: 100% !important; max-width: 100vw !important; box-sizing: border-box !important; overflow-x: hidden !important; }
        .client-content-container { min-height: 60vh !important; max-width: 100% !important; overflow-x: hidden !important; }
    }
</style>

<!-- Google Translate Engine Loader -->
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    // Theme Switcher Logic
    function toggleThemeMode() {
        var isLight = document.documentElement.classList.contains('light-mode') || document.body.classList.contains('light-mode');
        var targetMode = isLight ? 'dark' : 'light';
        
        if (targetMode === 'light') {
            document.documentElement.classList.add('light-mode');
            document.body.classList.add('light-mode');
            document.getElementById('themeModeIcon').className = 'fas fa-moon text-warning';
            document.getElementById('themeModeText').innerText = 'Dark Mode';
            localStorage.setItem('portal_theme', 'light');
        } else {
            document.documentElement.classList.remove('light-mode');
            document.body.classList.remove('light-mode');
            document.getElementById('themeModeIcon').className = 'fas fa-sun text-warning';
            document.getElementById('themeModeText').innerText = 'Light Mode';
            localStorage.setItem('portal_theme', 'dark');
        }
        // Dispatch event so Chatwoot widget & other components can sync
        try {
            window.dispatchEvent(new CustomEvent('ifw:theme_changed', { detail: { theme: targetMode } }));
        } catch(e) {}
    }

    function initThemeMode() {
        var savedTheme = localStorage.getItem('portal_theme') || 'dark';
        if (savedTheme === 'light') {
            document.documentElement.classList.add('light-mode');
            document.body.classList.add('light-mode');
            if (document.getElementById('themeModeIcon')) document.getElementById('themeModeIcon').className = 'fas fa-moon text-warning';
            if (document.getElementById('themeModeText')) document.getElementById('themeModeText').innerText = 'Dark Mode';
        }
    }

    // Currency Switcher Logic
    function changePortalCurrency(curr) {
        localStorage.setItem('portal_currency', curr);
        var label = document.getElementById('currentCurrencyLabel');
        if (label) label.innerText = curr;
        
        $.ajax({
            url: "{{ route('client.profile-info') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                preferred_currency: curr
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).always(function() {
            setTimeout(function() {
                location.reload();
            }, 100);
        });
    }

    function initPortalCurrency() {
        var savedCurrency = localStorage.getItem('portal_currency');
        if (savedCurrency) {
            var label = document.getElementById('currentCurrencyLabel');
            if (label) label.innerText = savedCurrency;
        }
    }

    // Google Translate Logic
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            autoDisplay: false,
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }

    function setPortalLanguage(langCode, langName, langFlag) {
        localStorage.setItem('portal_lang', langCode);
        localStorage.setItem('portal_lang_flag', langFlag);
        
        var flag = document.getElementById('currentLangFlag');
        var shortLabel = document.getElementById('currentLangShort');
        if (flag) flag.textContent = langFlag;
        if (shortLabel) shortLabel.textContent = langCode.toUpperCase().slice(0, 2);

        var host = window.location.hostname;
        document.cookie = "googtrans=/en/" + langCode + "; path=/; domain=" + host;
        document.cookie = "googtrans=/en/" + langCode + "; path=/;";

        var combo = document.querySelector('.goog-te-combo');
        if (combo) {
            combo.value = langCode;
            combo.dispatchEvent(new Event('change'));
        } else {
            location.reload();
        }
    }

    function initPortalLanguage() {
        var savedFlag = localStorage.getItem('portal_lang_flag') || '🇺🇸';
        var savedLang = localStorage.getItem('portal_lang') || 'en';
        var flag = document.getElementById('currentLangFlag');
        var shortLabel = document.getElementById('currentLangShort');
        if (flag) flag.textContent = savedFlag;
        if (shortLabel) shortLabel.textContent = savedLang.toUpperCase().slice(0, 2);
    }

    document.addEventListener("DOMContentLoaded", function() {
        initThemeMode();
        initPortalCurrency();
        initPortalLanguage();

        // Mobile drawer controls
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

        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    });
</script>

</body>
</html>
