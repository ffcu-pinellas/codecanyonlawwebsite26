@if(session()->has('impersonator_admin'))
<div style="position: sticky; top: 0; left: 0; width: 100%; z-index: 999999; background: #b45309; color: #ffffff; padding: 10px 20px; font-size: 13px; font-weight: bold; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.25); border-bottom: 2px solid #f59e0b;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="background: #ffffff; color: #b45309; padding: 2px 8px; border-radius: 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fas fa-user-secret mr-1"></i> Staff Impersonation Mode
        </span>
        <span>
            Viewing portal as <strong>{{ Auth::user()->name }}</strong> (Client ID #{{ sprintf('%05d', Auth::id()) }}) &bull; Logged in as Attorney/Admin: <em>{{ session('impersonator_admin.name', 'Admin') }}</em>
        </span>
    </div>
    <div>
        <a href="{{ route('admin.user.client.stop-impersonation') }}" style="background: #ffffff; color: #b45309; text-decoration: none; padding: 6px 16px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
            <i class="fas fa-sign-out-alt"></i> Exit Impersonation &amp; Return to Admin
        </a>
    </div>
</div>
@endif
