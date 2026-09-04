@if(session()->has('impersonator_admin') || session()->has('impersonated_by'))
<div style="position: sticky; top: 0; left: 0; width: 100%; z-index: 999999; background: linear-gradient(90deg, #9a3412 0%, #b45309 50%, #d97706 100%); color: #ffffff; padding: 10px 24px; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 16px rgba(0,0,0,0.35); border-bottom: 2px solid #f59e0b; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <span style="background: #ffffff; color: #9a3412; padding: 3px 10px; border-radius: 5px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 800; display: inline-flex; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">
            <i class="fas fa-user-secret mr-1"></i> {{ __('Staff Impersonation Mode') }}
        </span>
        <span style="color: #fef3c7;">
            {{ __('Viewing portal as') }} <strong>{{ Auth::user()->name }}</strong> (ID #{{ sprintf('%05d', Auth::id()) }}) &bull; {{ __('Signed in by Admin:') }} <em>{{ session('impersonator_admin.name', 'Super Admin') }}</em>
        </span>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
        <a href="{{ route('client.stop-impersonation') }}" style="background: #ffffff; color: #9a3412; text-decoration: none; padding: 6px 18px; border-radius: 5px; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; gap: 7px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); transition: all 0.15s ease;">
            <i class="fas fa-sign-out-alt"></i> {{ __('Exit Impersonation & Return to Admin') }}
        </a>
    </div>
</div>
@endif
