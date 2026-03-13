<div class="client-navigation px-0 rounded">
    <ul>
        <li class="{{ request()->is('client/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('client.dashboard') }}">
                <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="title">{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/conversation*') ? 'active' : '' }}">
            <a href="{{ route('client.conversation.index') }}">
                <span class="icon"><i class="fas fa-comment-dots"></i></span>
                <span class="title">{{ __('Messages') }}
                    @php
                        $unread = 0;
                        $conversations = Auth::user()->conversation;
                        foreach ($conversations as $conversation) {
                            $unread += $conversation->unreadMessages->where('user_id','!=',Auth::id())->count();
                        }
                    @endphp
                    @if ($unread > 0)
                        <i class="badge badge-info">{{ $unread }}</i>
                    @endif
                </span>
            </a>
        </li>
        <li class="{{ request()->is('client/profile*') ? 'active' : '' }}">
            <a href="{{ route('client.profile') }}">
                <span class="icon"><i class="fas fa-cog"></i></span>
                <span class="title">{{ __('Settings') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/financial-relief*') ? 'active' : '' }}">
            <a href="{{ route('client.financial-relief') }}">
                <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                <span class="title">{{ __('CPA / Legal Assistance') }}</span>
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
