<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark-mode">

@include('frontend.theme1.auth-client.layouts.head')

<body id="body" class="dark-mode" style="background-color: #0a0c10 !important; color: #f1f5f9; min-height: 100vh; overflow-x: hidden;">
@include('components.impersonation-bar')
@include('components.session-timeout-modal')
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

            <!-- Staff Top Notification Bell Dropdown -->
            @php
                $staffBellUnreadChat = 0;
                $staffBellPendingTasks = 0;
                $staffBellPendingDocs = 0;
                try {
                    $staffUser = Auth::user();
                    if ($staffUser) {
                        $staffOfficer = $staffUser->staffDetail?->officer ?: (\App\Models\User::role('admin')->first() ?: \App\Models\User::first());
                        if ($staffOfficer) {
                            $staffBellUnreadChat = \App\Models\StaffMessage::where('staff_user_id', $staffUser->id)
                                ->where('sender_id', $staffOfficer->id)
                                ->where('read', false)
                                ->count();
                        }
                        $staffBellPendingTasks = \App\Models\StaffTask::where('staff_user_id', $staffUser->id)
                            ->whereIn('status', ['pending', 'in_progress'])
                            ->count();
                        $staffBellPendingDocs = \App\Models\DocumentLog::where('staff_id', $staffUser->id)
                            ->whereIn('action_required', ['approve', 'sign_upload'])
                            ->whereNotIn('status', ['approved', 'signed', 'rejected'])
                            ->count();
                    }
                } catch (\Throwable $e) {}
                $staffBellTotal = $staffBellUnreadChat + $staffBellPendingTasks + $staffBellPendingDocs;
            @endphp
            <div class="dropdown position-relative">
                <button class="btn btn-sm btn-dark position-relative p-2" type="button" id="staffNotificationBellDropdown" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pending Actions & Notifications" style="background: #161a23; border: 1px solid #28303f; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-bell text-warning" style="font-size: 13.5px;"></i>
                    @if($staffBellTotal > 0)
                        <span class="position-absolute badge badge-danger rounded-circle font-weight-bold" style="top: -4px; right: -4px; font-size: 9.5px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 8px rgba(239, 68, 68, 0.8);">
                            {{ $staffBellTotal > 9 ? '9+' : $staffBellTotal }}
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-end shadow-lg p-0" id="staffNotificationMenu" aria-labelledby="staffNotificationBellDropdown" style="background: #161a23; border: 1px solid #28303f; width: 290px; max-width: calc(100vw - 24px); border-radius: 8px; overflow: hidden; right: 0 !important; left: auto !important;">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background: #11151e; border-color: #28303f !important;">
                        <span class="font-weight-bold text-white small text-uppercase" style="letter-spacing: 0.5px;">Staff Notifications</span>
                        <span class="badge {{ $staffBellTotal > 0 ? 'badge-warning text-dark' : 'badge-secondary' }} font-weight-bold" style="font-size: 10px;">
                            {{ $staffBellTotal }} Pending
                        </span>
                    </div>
                    <div class="p-2" style="max-height: 280px; overflow-y: auto;">
                        @if($staffBellUnreadChat > 0)
                            <a href="{{ route('staff.messages') }}" class="dropdown-item py-2 px-2 text-light rounded d-flex align-items-center mb-1" style="font-size: 12px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2);">
                                <div class="mr-2 text-danger" style="font-size: 16px;"><i class="fas fa-comments"></i></div>
                                <div style="min-width: 0;">
                                    <div class="font-weight-bold text-white text-truncate">{{ $staffBellUnreadChat }} Unread Team Chat</div>
                                    <small class="text-muted">Supervisor or counsel sent communication</small>
                                </div>
                            </a>
                        @endif

                        @if($staffBellPendingTasks > 0)
                            <a href="{{ route('staff.tasks.index') }}" class="dropdown-item py-2 px-2 text-light rounded d-flex align-items-center mb-1" style="font-size: 12px; background: rgba(254, 204, 86, 0.08); border: 1px solid rgba(254, 204, 86, 0.2);">
                                <div class="mr-2 text-warning" style="font-size: 16px;"><i class="fas fa-tasks"></i></div>
                                <div style="min-width: 0;">
                                    <div class="font-weight-bold text-white text-truncate">{{ $staffBellPendingTasks }} Assigned Task(s) Pending</div>
                                    <small class="text-muted">Awaiting your progression or review</small>
                                </div>
                            </a>
                        @endif

                        @if($staffBellPendingDocs > 0)
                            <a href="{{ route('staff.documents.index') }}" class="dropdown-item py-2 px-2 text-light rounded d-flex align-items-center mb-1" style="font-size: 12px; background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.2);">
                                <div class="mr-2 text-primary" style="font-size: 16px;"><i class="fas fa-file-contract"></i></div>
                                <div style="min-width: 0;">
                                    <div class="font-weight-bold text-white text-truncate">{{ $staffBellPendingDocs }} Document(s) Awaiting Action</div>
                                    <small class="text-muted">Review, approve, or sign uploaded documents</small>
                                </div>
                            </a>
                        @endif

                        @if($staffBellTotal == 0)
                            <div class="text-center py-3 text-muted small">
                                <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                <span>{{ __('All caught up! No pending tasks or messages.') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-2 text-center border-top" style="border-color: #28303f !important; background: #11151e;">
                        <a href="{{ route('staff.tasks.index') }}" class="small text-warning font-weight-bold text-decoration-none" style="font-size: 11px;">
                            {{ __('View Operations Workflow') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dark / Light Mode Switcher -->
            <button type="button" id="themeModeToggle" class="btn btn-sm btn-outline-secondary font-weight-bold d-flex align-items-center" onclick="toggleThemeMode()" title="Toggle Dark / Light Mode" style="border-radius: 20px; padding: 4px 10px; font-size: 11.5px; border-color: #3b4252; color: #cbd5e1; background: #161a23;">
                <i class="fas fa-sun text-warning" id="themeModeIcon"></i>
                <span id="themeModeText" class="d-none d-sm-inline ml-1">Light Mode</span>
            </button>

            <!-- Language Switcher Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-dark dropdown-toggle font-weight-bold px-2 py-1 staff-nav-btn" type="button" id="languageDropdown" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 6px; font-size: 11.5px;">
                    <span id="currentLangFlag">🇺🇸</span> <span id="currentLangShort" class="d-none d-sm-inline">EN</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow-lg p-2 staff-nav-dropdown" aria-labelledby="languageDropdown" style="min-width: 170px; max-height: 280px; overflow-y: auto;">
                    <a class="dropdown-item py-1 px-2 rounded d-flex align-items-center staff-dropdown-item" href="javascript:void(0);" onclick="setPortalLanguage('en', 'English', '🇺🇸')" style="font-size: 12px;">
                        <span class="mr-2">🇺🇸</span> <span>English</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded d-flex align-items-center staff-dropdown-item" href="javascript:void(0);" onclick="setPortalLanguage('es', 'Español', '🇪🇸')" style="font-size: 12px;">
                        <span class="mr-2">🇪🇸</span> <span>Español</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded d-flex align-items-center staff-dropdown-item" href="javascript:void(0);" onclick="setPortalLanguage('fr', 'Français', '🇫🇷')" style="font-size: 12px;">
                        <span class="mr-2">🇫🇷</span> <span>Français</span>
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded d-flex align-items-center staff-dropdown-item" href="javascript:void(0);" onclick="setPortalLanguage('de', 'Deutsch', '🇩🇪')" style="font-size: 12px;">
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
                <div class="dropdown-menu dropdown-menu-right shadow-lg p-2 staff-nav-dropdown" aria-labelledby="staffUserMenuDropdown" style="min-width: 220px;">
                    <div class="px-3 py-2 border-bottom mb-2 staff-dropdown-header" style="border-color: #28303f !important;">
                        <span class="d-block font-weight-bold small text-name">{{ Auth::user()->name }}</span>
                        <small class="text-muted d-block text-truncate">{{ Auth::user()->email }}</small>
                    </div>
                    <a class="dropdown-item staff-dropdown-item py-2 rounded" href="{{ route('staff.dashboard') }}" style="font-size: 13px;">
                        <i class="fas fa-tachometer-alt text-warning mr-2"></i> {{ __('Staff Dashboard') }}
                    </a>
                    <a class="dropdown-item staff-dropdown-item py-2 rounded" href="{{ route('staff.tasks.index') }}" style="font-size: 13px;">
                        <i class="fas fa-tasks text-warning mr-2"></i> {{ __('Assigned Tasks') }}
                    </a>
                    <a class="dropdown-item staff-dropdown-item py-2 rounded" href="{{ route('staff.financial-ledger') }}" style="font-size: 13px;">
                        <i class="fas fa-wallet text-warning mr-2"></i> {{ __('Financial Ledger') }}
                    </a>
                    <a class="dropdown-item staff-dropdown-item py-2 rounded" href="{{ route('staff.payment-method') }}" style="font-size: 13px;">
                        <i class="fas fa-university text-warning mr-2"></i> {{ __('Direct Deposit Setup') }}
                    </a>
                    <a class="dropdown-item staff-dropdown-item py-2 rounded" href="{{ route('staff.messages') }}" style="font-size: 13px;">
                        <i class="fas fa-comments text-warning mr-2"></i> {{ __('Internal Team Chat') }}
                    </a>
                    <a class="dropdown-item staff-dropdown-item py-2 rounded" href="{{ route('staff.profile') }}" style="font-size: 13px;">
                        <i class="fas fa-user-cog text-warning mr-2"></i> {{ __('Profile & Settings') }}
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
    <div class="staff-sidebar-col" id="staffSidebarCol" style="width: 250px; flex-shrink: 0; padding: 20px 0 20px 16px;">
        @include('frontend.theme1.auth-staff.menus.left-bar')
    </div>

    <!-- Content Column -->
    <div class="staff-content-col" style="flex: 1; min-width: 0; padding: 20px 16px 20px 16px;">
        <div class="staff-content-container p-3 p-md-4 rounded" style="background: #11151e; border: 1px solid #28303f; box-shadow: 0 4px 20px rgba(0,0,0,0.3); min-height: 80vh;">
            
            <!-- Mobile PWA Install Notification (Sleek Compact Pill) -->
            <div id="pwaInstallBanner" class="alert d-none mb-3 shadow-sm border-0 d-flex flex-wrap align-items-center justify-content-between py-2 px-3" style="border-radius: 8px; background: rgba(30, 58, 138, 0.9); border: 1px solid rgba(96, 165, 250, 0.3) !important; color: #fff;">
                <div class="d-flex align-items-center my-1">
                    <i class="fas fa-mobile-alt text-warning mr-2" style="font-size: 1.1rem;"></i>
                    <span class="small font-weight-bold">{{ __('Install Staff Workspace App for instantaneous mobile task & messaging access') }}</span>
                </div>
                <div class="d-flex align-items-center my-1">
                    <button type="button" id="pwaInstallBtn" class="btn btn-warning btn-sm font-weight-bold text-dark px-3 py-1 shadow-sm mr-2" style="font-size: 11.5px; border-radius: 6px;">
                        <i class="fas fa-download mr-1"></i> {{ __('Install App') }}
                    </button>
                    <button type="button" class="btn btn-link text-white btn-sm p-0 ml-1" onclick="dismissPwaBanner()" title="Dismiss">
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
    /* ===== MODERN THEME ENGINE FOR STAFF WORKSPACE ===== */
    html, body {
        font-family: 'Montserrat', sans-serif !important;
        background-color: #0a0c10 !important;
        color: #f1f5f9 !important;
        transition: background-color 0.25s ease, color 0.25s ease;
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

    /* Cards & containers dark */
    .card, .dashboard-card, .ledger-card, .payment-preference-card, .chat-card, .staff-settings-card {
        background: #11151e !important;
        border: 1px solid #28303f !important;
        color: #f1f5f9 !important;
    }
    .card-header, .chat-header, .staff-settings-card-header {
        background: #161a23 !important;
        border-color: #28303f !important;
        color: #f1f5f9 !important;
    }
    .card-body, .staff-settings-card-body {
        background: inherit !important;
        color: #f1f5f9 !important;
    }
    .staff-content-container {
        background: #11151e !important;
        border: 1px solid #28303f !important;
        color: #f1f5f9 !important;
    }

    /* Tables dark */
    table { color: #f1f5f9 !important; }
    thead th { color: #94a3b8 !important; background: #161a23 !important; border-color: #28303f !important; }
    tbody tr { border-color: #28303f !important; }
    tbody td { color: #e2e8f0 !important; border-color: #28303f !important; background: transparent !important; }
    tbody tr:hover td { background: rgba(255,255,255,0.03) !important; }

    /* Forms dark */
    .form-control, .form-select, select, input, textarea {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    .form-control:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254,204,86,0.2) !important;
        color: #f1f5f9 !important;
    }
    input::placeholder, textarea::placeholder { color: #64748b !important; }

    /* Modal dark */
    .modal-content {
        background: #11151e !important;
        border: 1px solid #28303f !important;
        color: #f1f5f9 !important;
    }
    .modal-header, .modal-footer {
        border-color: #28303f !important;
        background: #161a23 !important;
    }

    /* ===== COMPLETE LIGHT MODE OVERRIDES ===== */
    html.light-mode, body.light-mode {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }
    body.light-mode .staff-portal-wrapper {
        background-color: #f1f5f9 !important;
    }
    body.light-mode h1, body.light-mode h2, body.light-mode h3,
    body.light-mode h4, body.light-mode h5, body.light-mode h6,
    body.light-mode .card-title {
        color: #0f172a !important;
    }
    body.light-mode p,
    body.light-mode span:not(.badge):not(.text-warning):not(.text-success):not(.text-danger):not(.text-info),
    body.light-mode li, body.light-mode td, body.light-mode th {
        color: #334155 !important;
    }
    body.light-mode label, body.light-mode small {
        color: #64748b !important;
    }
    body.light-mode .text-muted { color: #64748b !important; }
    body.light-mode .text-white { color: #0f172a !important; }
    body.light-mode .text-dark { color: #0f172a !important; }
    body.light-mode strong, body.light-mode b { color: #0f172a !important; }
    body.light-mode .font-weight-bold { color: #0f172a !important; }
    body.light-mode [style*="color: #fecc56"],
    body.light-mode [style*="color:#fecc56"],
    body.light-mode .text-warning {
        color: #b45309 !important;
    }

    /* Containers light */
    body.light-mode .staff-content-container,
    body.light-mode .card,
    body.light-mode .dashboard-card,
    body.light-mode .ledger-card,
    body.light-mode .payment-preference-card,
    body.light-mode .staff-settings-card,
    body.light-mode .staff-profile-card,
    body.light-mode .top-punch-dock {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
        color: #0f172a !important;
    }

    /* PWA Banner light mode */
    body.light-mode #pwaInstallBanner {
        background: #eff6ff !important;
        border: 1px solid #93c5fd !important;
        color: #1e40af !important;
    }
    body.light-mode #pwaInstallBanner span {
        color: #1e40af !important;
    }
    body.light-mode #pwaInstallBanner .btn-link {
        color: #1e40af !important;
    }

    .staff-nav-btn {
        background: #161a23;
        border: 1px solid #28303f;
        color: #f1f5f9;
    }
    .staff-nav-dropdown {
        background: #161a23 !important;
        border: 1px solid #28303f !important;
    }
    .staff-dropdown-item {
        color: #cbd5e1 !important;
    }
    .staff-dropdown-item:hover {
        background: #28303f !important;
        color: #fecc56 !important;
    }
    .staff-dropdown-header .text-name {
        color: #ffffff !important;
    }

    /* Top utility controls light mode */
    body.light-mode #languageDropdown,
    body.light-mode .staff-nav-btn {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode .staff-nav-dropdown {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    body.light-mode .staff-dropdown-item {
        color: #1e293b !important;
    }
    body.light-mode .staff-dropdown-item:hover {
        background: #f1f5f9 !important;
        color: #b45309 !important;
    }
    body.light-mode .staff-dropdown-header {
        border-color: #e2e8f0 !important;
    }
    body.light-mode .staff-dropdown-header .text-name {
        color: #0f172a !important;
    }
    body.light-mode #themeModeToggle {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode #themeModeToggle span {
        color: #0f172a !important;
    }
    body.light-mode .dropdown-menu {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    body.light-mode .dropdown-menu .dropdown-item {
        color: #1e293b !important;
    }
    body.light-mode .dropdown-menu .dropdown-item:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    body.light-mode .card-header,
    body.light-mode .chat-header,
    body.light-mode .staff-settings-card-header {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
    body.light-mode header.client-portal-navbar {
        background: #ffffff !important;
        border-bottom-color: #e2e8f0 !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06) !important;
    }
    body.light-mode header.client-portal-navbar .text-white { color: #0f172a !important; }
    body.light-mode header.client-portal-navbar .btn-outline-secondary {
        border-color: #cbd5e1 !important;
        color: #475569 !important;
        background: #f8fafc !important;
    }
    body.light-mode header.client-portal-navbar .dropdown-menu {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode header.client-portal-navbar .dropdown-item {
        color: #374151 !important;
    }
    body.light-mode header.client-portal-navbar .dropdown-item:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }

    /* Metric cards light */
    body.light-mode .metric-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
    }
    body.light-mode .metric-card h3 { color: #0f172a !important; }

    /* Tables light */
    body.light-mode table { color: #334155 !important; }
    body.light-mode thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode tbody td {
        color: #374151 !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode tbody tr:hover td { background: #f8fafc !important; }

    /* Forms light */
    body.light-mode .form-control, body.light-mode select, body.light-mode input, body.light-mode textarea {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode .form-control:focus {
        border-color: #fecc56 !important;
        box-shadow: 0 0 0 2px rgba(254,204,86,0.25) !important;
    }
    body.light-mode input::placeholder, body.light-mode textarea::placeholder {
        color: #94a3b8 !important;
    }

    /* Chat Light Mode */
    body.light-mode .chat-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .chat-messages {
        background: #f8fafc !important;
    }
    body.light-mode .chat-bubble-received {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode .chat-bubble-received .chat-text {
        color: #0f172a !important;
    }
    body.light-mode .chat-footer {
        background: #ffffff !important;
        border-top-color: #e2e8f0 !important;
    }

    /* Kanban Light Mode */
    body.light-mode .kanban-column {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .kanban-card {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }
    body.light-mode .kanban-card h6 {
        color: #0f172a !important;
    }

    /* Modals light */
    body.light-mode .modal-content {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
    body.light-mode .modal-header, body.light-mode .modal-footer {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }

    /* Mobile responsive sidebar */
    @media (max-width: 991px) {
        .staff-sidebar-col {
            position: fixed;
            top: 0;
            left: -290px;
            width: 280px !important;
            height: 100vh;
            z-index: 1099;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #11151e;
            padding: 0 !important;
        }
        body.light-mode .staff-sidebar-col {
            background: #ffffff !important;
        }
        .staff-sidebar-col.show, .staff-sidebar-col.active {
            left: 0 !important;
            display: block !important;
            box-shadow: 10px 0 30px rgba(0,0,0,0.7) !important;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.65);
            z-index: 1090;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active {
            display: block !important;
        }
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
            var icon = document.getElementById('themeModeIcon');
            var text = document.getElementById('themeModeText');
            if (icon) icon.className = 'fas fa-moon text-warning';
            if (text) text.innerText = 'Dark Mode';
            localStorage.setItem('portal_theme', 'light');
        } else {
            document.documentElement.classList.remove('light-mode');
            document.body.classList.remove('light-mode');
            var icon = document.getElementById('themeModeIcon');
            var text = document.getElementById('themeModeText');
            if (icon) icon.className = 'fas fa-sun text-warning';
            if (text) text.innerText = 'Light Mode';
            localStorage.setItem('portal_theme', 'dark');
        }
    }

    function initThemeMode() {
        var savedTheme = localStorage.getItem('portal_theme') || 'dark';
        if (savedTheme === 'light') {
            document.documentElement.classList.add('light-mode');
            document.body.classList.add('light-mode');
            var icon = document.getElementById('themeModeIcon');
            var text = document.getElementById('themeModeText');
            if (icon) icon.className = 'fas fa-moon text-warning';
            if (text) text.innerText = 'Dark Mode';
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

    // Mobile Sidebar Toggle
    document.addEventListener('DOMContentLoaded', function() {
        initThemeMode();
        initPortalLanguage();

        const toggleBtn = document.getElementById('staffMobileToggleBtn');
        const sidebar = document.getElementById('staffSidebarCol');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('mobileCloseSidebar');

        if (toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        }
    });

    // PWA Service Worker Registration & Smart Install Banner
    let deferredPwaPrompt = null;
    const pwaBanner = document.getElementById('pwaInstallBanner');
    const pwaInstallBtn = document.getElementById('pwaInstallBtn');

    const isPwaInstalled = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true
        || (document.referrer && document.referrer.includes('android-app://'))
        || localStorage.getItem('staff_pwa_installed') === 'true';

    if (isPwaInstalled && pwaBanner) {
        pwaBanner.remove();
    }

    window.addEventListener('appinstalled', () => {
        localStorage.setItem('staff_pwa_installed', 'true');
        if (pwaBanner) pwaBanner.remove();
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Staff PWA SW registered:', reg.scope))
                .catch(err => console.warn('Staff PWA SW error:', err));
        });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        if (isPwaInstalled || localStorage.getItem('staff_pwa_installed') === 'true') {
            return;
        }
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
                localStorage.setItem('staff_pwa_installed', 'true');
                if (pwaBanner) pwaBanner.remove();
            }
            deferredPwaPrompt = null;
        });
    }

    function dismissPwaBanner() {
        if (pwaBanner) pwaBanner.classList.add('d-none');
        localStorage.setItem('staff_pwa_banner_dismissed', 'true');
    }
</script>
@include('components.webrtc-call-modal')
</body>
</html>
