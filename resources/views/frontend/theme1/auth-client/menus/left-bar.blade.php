<div class="client-navigation px-0 rounded" style="background: #161a23; border: 1px solid #28303f; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.3);">
    <div class="mobile-close-btn" id="mobileCloseSidebar" style="color: #fecc56; padding: 10px 15px; cursor: pointer; text-align: right;"><i class="fas fa-times"></i></div>
    
    <!-- Client Identity Mini Widget -->
    <div class="p-3 text-center border-bottom" style="border-color: #28303f !important; background: #11151e;">
        <div class="mb-2">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-thumbnail" style="width: 60px; height: 60px; object-fit: cover; border-color: #fecc56;">
            @else
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(254,204,86,0.15); color: #fecc56; font-size: 22px; font-weight: bold; border: 2px solid #fecc56;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <h6 class="font-weight-bold text-white mb-0" style="font-size: 14px;">{{ Auth::user()->name }}</h6>
        <div class="badge mt-1" style="background: rgba(254, 204, 86, 0.15); color: #fecc56; border: 1px solid rgba(254, 204, 86, 0.3); font-size: 11px;">
            CLI-{{ sprintf('%05d', Auth::user()->id) }}
        </div>
    </div>

    <ul class="list-unstyled mb-0 py-2">
        <li class="{{ request()->is('client/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('client.dashboard') }}">
                <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="title">{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/conversation*') ? 'active' : '' }}">
            <a href="{{ route('client.conversation.index') }}">
                <span class="icon"><i class="fas fa-comments"></i></span>
                <span class="title">{{ __('Live Support & Chat') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/cases*') ? 'active' : '' }}">
            <a href="{{ route('client.cases.index') }}">
                <span class="icon"><i class="fas fa-briefcase"></i></span>
                <span class="title">{{ __('My Cases') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/kyc-documents*') ? 'active' : '' }}">
            <a href="{{ route('client.kyc.index') }}">
                <span class="icon"><i class="fas fa-file-upload"></i></span>
                <span class="title">{{ __('Upload Documents') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/invoices*') ? 'active' : '' }}">
            <a href="{{ route('client.invoices.index') }}">
                <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                <span class="title">{{ __('Invoices & Retainers') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/document-center*') ? 'active' : '' }}">
            <a href="{{ route('client.documents.index') }}">
                <span class="icon"><i class="fas fa-file-contract"></i></span>
                <span class="title">{{ __('Contracts & Agreements') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/financial-relief*') ? 'active' : '' }}">
            <a href="{{ route('client.financial-relief') }}">
                <span class="icon"><i class="fas fa-folder-plus"></i></span>
                <span class="title">{{ __('Open New Case') }}</span>
            </a>
        </li>
        <li class="{{ request()->is('client/profile*') ? 'active' : '' }}">
            <a href="{{ route('client.profile') }}">
                <span class="icon"><i class="fas fa-shield-alt"></i></span>
                <span class="title">{{ __('Security & PIN Settings') }}</span>
            </a>
        </li>
        <li class="border-top mt-2 pt-2" style="border-color: #28303f !important;">
            <form action="{{ route('logout') }}" method="POST" id="logoutFormClientSidebar">
                @csrf
                <a href="javascript:void(0)" onclick="document.getElementById('logoutFormClientSidebar').submit();" class="text-danger">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="title">{{ __('Sign Out') }}</span>
                </a>
            </form>
        </li>
    </ul>
</div>

<style>
    .client-navigation ul li a {
        display: flex;
        align-items: center;
        padding: 12px 18px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 600;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    .client-navigation ul li a:hover {
        background: #1f2533;
        color: #ffffff;
        border-left-color: #fecc56;
    }
    .client-navigation ul li.active a {
        background: linear-gradient(90deg, rgba(254,204,86,0.15) 0%, rgba(22,26,35,0) 100%);
        color: #fecc56;
        border-left-color: #fecc56;
        font-weight: 700;
    }
    .client-navigation ul li a .icon {
        width: 28px;
        font-size: 15px;
        margin-right: 10px;
        display: inline-flex;
        align-items: center;
    }
</style>
