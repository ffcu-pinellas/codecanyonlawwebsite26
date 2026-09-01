<!-- IFW Luxury Client Navigation Sidebar -->
<div class="ifw-client-sidebar" id="ifwClientSidebar">
    <!-- Close Button (Mobile Only) -->
    <div class="d-lg-none d-flex justify-content-between align-items-center p-3 border-bottom border-secondary" style="background: #11151e;">
        <span class="font-weight-bold text-warning small text-uppercase" style="letter-spacing: 0.5px;">Navigation Menu</span>
        <button type="button" class="btn btn-sm btn-dark text-warning p-1" id="mobileCloseSidebar" style="border: 1px solid #28303f;">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>

    <!-- Client Profile Identity Widget -->
    <div class="p-3 text-center border-bottom ifw-sidebar-user-card" style="border-color: #28303f !important; background: #11151e;">
        <div class="mb-2">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-thumbnail" style="width: 56px; height: 56px; object-fit: cover; border-color: #fecc56;">
            @else
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 20px; font-weight: bold; border: 2px solid #fecc56;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
        @php
            $uKycApproved = (Auth::user()->kyc_verified_at || (Auth::user()->kycDocuments && Auth::user()->kycDocuments->where('status', 'approved')->count() > 0));
            $uKycPending = (!$uKycApproved && Auth::user()->kycDocuments && Auth::user()->kycDocuments->whereIn('status', ['pending', 'submitted', 'under_review'])->count() > 0);
        @endphp
        @if($uKycApproved)
            <div class="badge mt-1 font-weight-bold" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); font-size: 11px;">
                <i class="fas fa-check-circle mr-1"></i> {{ __('Verified Client') }}
            </div>
        @elseif($uKycPending)
            <div class="badge mt-1 font-weight-bold" style="background: rgba(254, 204, 86, 0.15); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 11px;">
                <i class="fas fa-clock mr-1"></i> {{ __('KYC Under Review') }}
            </div>
        @else
            <a href="{{ route('client.kyc.index') }}" class="badge mt-1 font-weight-bold d-inline-block text-decoration-none" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-size: 10.5px;">
                <i class="fas fa-user-clock mr-1"></i> {{ __('KYC Required') }}
            </a>
        @endif
    </div>

    <!-- Navigation List -->
    <ul class="list-unstyled mb-0 py-2 ifw-nav-list">
        <li class="{{ request()->is('client/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('client.dashboard') }}">
                <i class="fas fa-tachometer-alt nav-icon"></i>
                <span class="nav-text">{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/conversation*') ? 'active' : '' }}">
            <a href="{{ route('client.conversation.index') }}">
                <i class="fas fa-comments nav-icon"></i>
                <span class="nav-text">{{ __('Live Support & Chat') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/cases*') ? 'active' : '' }}">
            <a href="{{ route('client.cases.index') }}">
                <i class="fas fa-briefcase nav-icon"></i>
                <span class="nav-text">{{ __('My Cases') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/kyc') ? 'active' : '' }}">
            <a href="{{ route('client.kyc.hub') }}">
                <i class="fas fa-id-card nav-icon"></i>
                <span class="nav-text">{{ __('Identity Verification (KYC)') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/kyc-documents*') ? 'active' : '' }}">
            <a href="{{ route('client.kyc.index') }}">
                <i class="fas fa-file-upload nav-icon"></i>
                <span class="nav-text">{{ __('Upload Documents') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/invoices*') ? 'active' : '' }}">
            <a href="{{ route('client.invoices.index') }}">
                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                <span class="nav-text">{{ __('Invoices & Retainers') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/document-center*') ? 'active' : '' }}">
            <a href="{{ route('client.documents.index') }}">
                <i class="fas fa-file-contract nav-icon"></i>
                <span class="nav-text">{{ __('Contracts & Agreements') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/financial-relief*') ? 'active' : '' }}">
            <a href="{{ route('client.financial-relief') }}">
                <i class="fas fa-folder-plus nav-icon"></i>
                <span class="nav-text">{{ __('Open New Case') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/profile*') ? 'active' : '' }}">
            <a href="{{ route('client.profile') }}">
                <i class="fas fa-shield-alt nav-icon"></i>
                <span class="nav-text">{{ __('Security & PIN') }}</span>
            </a>
        </li>
        <li class="border-top mt-2 pt-2 px-3 pb-1" style="border-color: #28303f !important;">
            <form action="{{ route('logout') }}" method="POST" id="logoutFormClientSidebar">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm btn-block font-weight-bold d-flex align-items-center justify-content-center py-2" style="border-radius: 8px; font-size: 12.5px;">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Sign Out') }}
                </button>
            </form>
        </li>
    </ul>
</div>

<style>
    /* Reset and enforce IFW Sidebar styling with high specificity */
    .ifw-client-sidebar {
        background: #161a23 !important;
        border: 1px solid #28303f !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3) !important;
        width: 100% !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list {
        list-style: none !important;
        padding: 8px 0 !important;
        margin: 0 !important;
        display: block !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list > li {
        list-style: none !important;
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list > li > a {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: flex-start !important;
        padding: 12px 18px !important;
        color: #94a3b8 !important;
        text-decoration: none !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        border-left: 3px solid transparent !important;
        white-space: nowrap !important;
        width: 100% !important;
        box-sizing: border-box !important;
        text-align: left !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list > li > a:hover {
        background: #1f2533 !important;
        color: #ffffff !important;
        border-left-color: #fecc56 !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list > li.active > a {
        background: linear-gradient(90deg, rgba(254,204,86,0.15) 0%, rgba(22,26,35,0) 100%) !important;
        color: #fecc56 !important;
        border-left-color: #fecc56 !important;
        font-weight: 700 !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list > li > a .nav-icon {
        width: 24px !important;
        font-size: 15px !important;
        margin-right: 12px !important;
        flex-shrink: 0 !important;
        text-align: center !important;
        color: #fecc56 !important;
    }
    .ifw-client-sidebar ul.ifw-nav-list > li > a .nav-text {
        font-size: 13.5px !important;
        line-height: 1.3 !important;
        display: inline-block !important;
        white-space: nowrap !important;
    }

    /* Light Mode */
    body.light-mode .ifw-client-sidebar, html.light-mode .ifw-client-sidebar {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .ifw-sidebar-user-card, html.light-mode .ifw-sidebar-user-card {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .ifw-sidebar-user-name, html.light-mode .ifw-sidebar-user-name {
        color: #0f172a !important;
    }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a,
    html.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a {
        color: #475569 !important;
    }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a:hover,
    html.light-mode .ifw-client-sidebar ul.ifw-nav-list > li > a:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    body.light-mode .ifw-client-sidebar ul.ifw-nav-list > li.active > a,
    html.light-mode .ifw-client-sidebar ul.ifw-nav-list > li.active > a {
        background: rgba(254,204,86,0.15) !important;
        color: #b45309 !important;
        border-left-color: #fecc56 !important;
    }

    /* Mobile Drawer */
    @media (max-width: 991px) {
        .ifw-client-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: -290px !important;
            width: 280px !important;
            max-width: 85vw !important;
            height: 100vh !important;
            z-index: 1099 !important;
            border-radius: 0 !important;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            overflow-y: auto !important;
        }
        .ifw-client-sidebar.active {
            left: 0 !important;
            box-shadow: 10px 0 30px rgba(0,0,0,0.8) !important;
        }
    }
</style>
