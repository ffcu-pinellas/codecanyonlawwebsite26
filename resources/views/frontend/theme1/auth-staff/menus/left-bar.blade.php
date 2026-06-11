<div class="client-navigation px-0 rounded">
    <div class="mobile-close-btn" id="mobileCloseSidebar"><i class="fas fa-times"></i></div>
    <ul>
        <li class="{{ request()->is('staff/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('staff.dashboard') }}">
                <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="title">{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/tasks*') ? 'active' : '' }}">
            <a href="{{ route('staff.tasks.index') }}">
                <span class="icon"><i class="fas fa-tasks"></i></span>
                <span class="title">{{ __('Assigned Tasks') }}
                    @php
                        $pendingTasksCount = 0;
                        if (Auth::check()) {
                            $pendingTasksCount = \App\Models\StaffTask::where('staff_user_id', Auth::id())
                                ->whereIn('status', ['pending', 'in_progress'])
                                ->count();
                        }
                    @endphp
                    @if ($pendingTasksCount > 0)
                        <span class="badge badge-warning ml-1">{{ $pendingTasksCount }}</span>
                    @endif
                </span>
            </a>
        </li>
        <li class="{{ request()->is('staff/financial-ledger*') ? 'active' : '' }}">
            <a href="{{ route('staff.financial-ledger') }}">
                <span class="icon"><i class="fas fa-wallet"></i></span>
                <span class="title">{{ __('Financial Ledger') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/payment-method*') ? 'active' : '' }}">
            <a href="{{ route('staff.payment-method') }}">
                <span class="icon"><i class="fas fa-credit-card"></i></span>
                <span class="title">{{ __('Payment Management') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/invoices*') ? 'active' : '' }}">
            <a href="{{ route('staff.invoices.index') }}">
                <span class="icon"><i class="fas fa-receipt"></i></span>
                <span class="title">{{ __('Invoices') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('staff/messages*') ? 'active' : '' }}">
            <a href="{{ route('staff.messages') }}">
                <span class="icon"><i class="fas fa-comment-dots"></i></span>
                <span class="title">{{ __('Chat / Communications') }}
                    @php
                        $unread = 0;
                        if (Auth::check() && Auth::user()->staffDetail) {
                            $officer = Auth::user()->staffDetail->officer;
                            if (!$officer) {
                                $officer = \App\Models\User::role('admin')->first() ?: \App\Models\User::first();
                            }
                            if ($officer) {
                                $unread = \App\Models\StaffMessage::where('staff_user_id', Auth::id())
                                    ->where('officer_user_id', $officer->id)
                                    ->where('sender_id', $officer->id)
                                    ->where('read', false)
                                    ->count();
                            }
                        }
                    @endphp
                    @if ($unread > 0)
                        <span class="badge badge-info ml-1">{{ $unread }}</span>
                    @endif
                </span>
            </a>
        </li>
        <li>
            <a id="logOut" href="javascript:void(0);">
                <span class="icon"><i class="fas fa-power-off"></i></span>
                <span class="title">{{ __('Log Out') }}</span>
                <form action="{{ route('logout') }}" method="post" id="logOutForm">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</div>
