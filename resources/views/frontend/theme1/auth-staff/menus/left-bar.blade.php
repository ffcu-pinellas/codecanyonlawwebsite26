<!-- Executive Staff Navigation Sidebar -->
<div class="ifw-staff-sidebar" id="ifwStaffSidebar">
    <!-- Close Button (Mobile Only) -->
    <div class="d-lg-none d-flex justify-content-between align-items-center p-3 border-bottom border-secondary" style="background: #11151e;">
        <span class="font-weight-bold text-warning small text-uppercase" style="letter-spacing: 0.5px;">{{ __('Staff Navigation') }}</span>
        <button type="button" class="btn btn-sm btn-dark text-warning p-1" id="mobileCloseSidebar" style="border: 1px solid #28303f;">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>

    <!-- Staff Profile Identity Widget -->
    <div class="p-3 text-center border-bottom ifw-sidebar-user-card" style="border-color: #28303f !important; background: #11151e;">
        <div class="mb-2">
            @if(Auth::check() && Auth::user()->profile_photo_path)
                @php
                    $uPhoto = Auth::user()->profile_photo_path;
                    $uSrc = str_starts_with($uPhoto, 'assets/') || str_starts_with($uPhoto, 'http') || str_starts_with($uPhoto, '/assets') ? asset($uPhoto) : Storage::url($uPhoto);
                @endphp
                <img src="{{ $uSrc }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-thumbnail" style="width: 52px; height: 52px; object-fit: cover; border-color: #fecc56;">
            @else
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 19px; font-weight: 800; border: 2px solid #fecc56;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="font-weight-bold text-white small ifw-sidebar-user-name mb-1" style="line-height: 1.2;">
            {{ Auth::user()->name ?? 'Staff Associate' }}
        </div>
        <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 4px;">
            <span class="badge" style="background: rgba(254, 204, 86, 0.12); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 10px;">
                ID: {{ Auth::user()->staffDetail?->staff_id ?: 'STF-' . sprintf('%04d', Auth::id()) }}
            </span>
            @php
                $isClocked = Auth::check() && \App\Models\StaffTimeLog::where('user_id', Auth::id())->whereNull('clocked_out_at')->exists();
            @endphp
            @if($isClocked)
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 10px;">
                    <i class="fas fa-circle mr-1" style="font-size: 6px;"></i> {{ __('On Shift') }}
                </span>
            @else
                <span class="badge" style="background: rgba(148, 163, 184, 0.12); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.25); font-size: 10px;">
                    {{ __('Off Duty') }}
                </span>
            @endif
        </div>
    </div>

    <!-- Navigation List -->
    <ul class="list-unstyled mb-0 py-2 ifw-nav-list">
        <li class="{{ request()->is('staff/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('staff.dashboard') }}">
                <i class="fas fa-tachometer-alt nav-icon"></i>
                <span class="nav-text">{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/tasks*') ? 'active' : '' }}">
            <a href="{{ route('staff.tasks.index') }}">
                <i class="fas fa-tasks nav-icon"></i>
                <span class="nav-text">{{ __('Assigned Tasks') }}</span>
                @php
                    $pendingTasksCount = 0;
                    if (Auth::check()) {
                        $pendingTasksCount = \App\Models\StaffTask::where('staff_user_id', Auth::id())
                            ->whereIn('status', ['pending', 'in_progress'])
                            ->count();
                    }
                @endphp
                @if ($pendingTasksCount > 0)
                    <span class="badge badge-warning ml-auto font-weight-bold" style="font-size: 10.5px; border-radius: 10px; padding: 2px 7px;">{{ $pendingTasksCount }}</span>
                @endif
            </a>
        </li>
        <li class="{{ request()->is('staff/financial-ledger*') ? 'active' : '' }}">
            <a href="{{ route('staff.financial-ledger') }}">
                <i class="fas fa-wallet nav-icon"></i>
                <span class="nav-text">{{ __('Financial Ledger') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/payment-method*') ? 'active' : '' }}">
            <a href="{{ route('staff.payment-method') }}">
                <i class="fas fa-university nav-icon"></i>
                <span class="nav-text">{{ __('Direct Deposit') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/invoices*') ? 'active' : '' }}">
            <a href="{{ route('staff.invoices.index') }}">
                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                <span class="nav-text">{{ __('Invoices & Billing') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/document-center*') ? 'active' : '' }}">
            <a href="{{ route('staff.documents.index') }}">
                <i class="fas fa-folder-open nav-icon"></i>
                <span class="nav-text">{{ __('Document Center') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/messages*') ? 'active' : '' }}">
            <a href="{{ route('staff.messages') }}">
                <i class="fas fa-comments nav-icon"></i>
                <span class="nav-text">{{ __('Internal Team Chat') }}</span>
                @php
                    $unreadStaffChat = 0;
                    if (Auth::check()) {
                        $officer = Auth::user()->staffDetail?->officer ?: (\App\Models\User::role('admin')->first() ?: \App\Models\User::first());
                        if ($officer) {
                            $unreadStaffChat = \App\Models\StaffMessage::where('staff_user_id', Auth::id())
                                ->where('officer_user_id', $officer->id)
                                ->where('sender_id', $officer->id)
                                ->where('read', false)
                                ->count();
                        }
                    }
                @endphp
                @if ($unreadStaffChat > 0)
                    <span class="badge badge-warning ml-auto font-weight-bold" style="font-size: 10.5px; border-radius: 10px; padding: 2px 7px;">{{ $unreadStaffChat }}</span>
                @endif
            </a>
        </li>
        <li class="{{ request()->is('staff/profile*') ? 'active' : '' }}">
            <a href="{{ route('staff.profile') }}">
                <i class="fas fa-user-cog nav-icon"></i>
                <span class="nav-text">{{ __('Profile & Settings') }}</span>
            </a>
        </li>
        <li class="border-top border-secondary mt-2 pt-2">
            <a href="javascript:void(0);" onclick="document.getElementById('staffSideLogoutForm').submit();" class="text-danger">
                <i class="fas fa-sign-out-alt nav-icon text-danger"></i>
                <span class="nav-text text-danger">{{ __('Log Out') }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" id="staffSideLogoutForm" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>

<style>
    @media (min-width: 992px) {
        .staff-sidebar-col {
            position: sticky !important;
            top: 75px !important;
            height: calc(100vh - 95px) !important;
            max-height: calc(100vh - 95px) !important;
            padding: 10px 0 10px 16px !important;
            align-self: flex-start !important;
            z-index: 90 !important;
        }
        .ifw-staff-sidebar {
            height: 100% !important;
            min-height: 100% !important;
            max-height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .ifw-staff-sidebar ul.ifw-nav-list {
            flex: 1 1 auto !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }
    }
    .ifw-staff-sidebar {
        background: #11151e !important;
        border: 1px solid #28303f !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3) !important;
        width: 100% !important;
        height: 100% !important;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #334155 transparent;
    }
    .ifw-staff-sidebar::-webkit-scrollbar {
        width: 4px;
    }
    .ifw-staff-sidebar::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 4px;
    }
    .ifw-staff-sidebar ul.ifw-nav-list {
        list-style: none !important;
        padding: 8px 0 !important;
        margin: 0 !important;
        display: block !important;
    }
    .ifw-staff-sidebar ul.ifw-nav-list > li {
        list-style: none !important;
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    .ifw-staff-sidebar ul.ifw-nav-list > li > a {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: flex-start !important;
        padding: 13px 18px !important;
        margin: 7px 12px !important;
        border-radius: 8px !important;
        color: #94a3b8 !important;
        text-decoration: none !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        border-left: 3px solid transparent !important;
        white-space: nowrap !important;
        box-sizing: border-box !important;
        text-align: left !important;
    }
    .ifw-staff-sidebar ul.ifw-nav-list > li > a:hover {
        background: #1f2533 !important;
        color: #ffffff !important;
        border-left-color: #fecc56 !important;
    }
    .ifw-staff-sidebar ul.ifw-nav-list > li.active > a {
        background: linear-gradient(90deg, rgba(254,204,86,0.15) 0%, rgba(22,26,35,0.2) 100%) !important;
        color: #fecc56 !important;
        border-left-color: #fecc56 !important;
        font-weight: 700 !important;
    }
    .ifw-staff-sidebar ul.ifw-nav-list > li > a .nav-icon {
        width: 24px !important;
        font-size: 15px !important;
        margin-right: 12px !important;
        flex-shrink: 0 !important;
        text-align: center !important;
        color: #fecc56 !important;
    }
    .ifw-staff-sidebar ul.ifw-nav-list > li > a .nav-text {
        font-size: 13.5px !important;
        line-height: 1.25 !important;
        display: inline-block !important;
        white-space: nowrap !important;
    }

    /* Light Mode */
    body.light-mode .ifw-staff-sidebar, html.light-mode .ifw-staff-sidebar {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    }
    body.light-mode .ifw-staff-sidebar .ifw-sidebar-user-card {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    body.light-mode .ifw-staff-sidebar .ifw-sidebar-user-name {
        color: #0f172a !important;
    }
    body.light-mode .ifw-staff-sidebar ul.ifw-nav-list > li > a {
        color: #475569 !important;
    }
    body.light-mode .ifw-staff-sidebar ul.ifw-nav-list > li > a:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    body.light-mode .ifw-staff-sidebar ul.ifw-nav-list > li.active > a {
        background: rgba(254,204,86,0.15) !important;
        color: #b45309 !important;
        border-left-color: #fecc56 !important;
    }

    /* Mobile Drawer */
    @media (max-width: 991px) {
        .staff-sidebar-col {
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
            padding: 0 !important;
            background: #11151e !important;
        }
        .staff-sidebar-col.active,
        .staff-sidebar-col.show {
            left: 0 !important;
            box-shadow: 10px 0 30px rgba(0,0,0,0.8) !important;
            display: block !important;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.65);
            z-index: 1090;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active {
            display: block !important;
        }
    }
</style>
