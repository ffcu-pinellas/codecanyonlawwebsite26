@php
    $currentUser = Auth::user();
    $passkeys = $currentUser->passkey_credentials ?? [];
    if (!is_array($passkeys)) {
        $passkeys = [];
    }
@endphp

<div class="card mb-4 border-0 shadow-sm" style="background: #161a23; border: 1px solid #28303f !important; border-radius: 12px; overflow: hidden;">
    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4" style="background: #1f2533; border-bottom: 1px solid #2e3849;">
        <div class="d-flex align-items-center">
            <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(254, 204, 86, 0.15); color: #fecc56; font-size: 18px;">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div>
                <h6 class="font-weight-bold text-white mb-0" style="font-size: 14px;">{{ __('Biometric Passkeys (Touch ID / Face ID / Windows Hello)') }}</h6>
                <small class="text-muted" style="font-size: 11.5px;">{{ __('FIDO2 / WebAuthn cryptographic passwordless 2FA device authentication.') }}</small>
            </div>
        </div>
        <span class="badge {{ count($passkeys) > 0 ? 'badge-success' : 'badge-warning text-dark' }} px-3 py-1 font-weight-bold" style="font-size: 11px;">
            <i class="fas {{ count($passkeys) > 0 ? 'fa-shield-check' : 'fa-shield-alt' }} mr-1"></i>
            {{ count($passkeys) > 0 ? count($passkeys) . ' ' . __('Device(s) Active') : __('Not Configured') }}
        </span>
    </div>

    <div class="card-body p-4 text-left">
        <p class="text-muted small mb-3">
            {{ __('Register your phone, tablet, or PC biometrics to bypass email codes and unlock the 2FA Security Gate instantly with your fingerprint, facial recognition, or hardware security key.') }}
        </p>

        <!-- Registered Devices List -->
        @if(count($passkeys) > 0)
            <div class="mb-4">
                <label class="small font-weight-bold text-warning mb-2 d-block">
                    <i class="fas fa-laptop-code mr-1"></i> {{ __('Registered Passkey Devices') }} ({{ count($passkeys) }})
                </label>
                <div class="list-group" style="gap: 8px;">
                    @foreach($passkeys as $idx => $passkey)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3 rounded" style="background: #11151e; border: 1px solid #28303f; color: #f1f5f9;">
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-white" style="font-size: 13.5px;">
                                        {{ $passkey['name'] ?? __('Biometric Passkey Device') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 11px;">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ __('Added:') }} {{ isset($passkey['created_at']) ? \Carbon\Carbon::parse($passkey['created_at'])->format('M d, Y h:i A') : __('Active') }}
                                        &bull; <code style="color: #fecc56; font-size: 10.5px;">••••{{ substr($passkey['id'] ?? 'xxxx', -6) }}</code>
                                    </small>
                                </div>
                            </div>
                            <form action="{{ route('auth.passkey.remove', $idx) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to remove this biometric passkey from your account?') }}');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                                    <i class="fas fa-trash-alt mr-1"></i> {{ __('Remove') }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="p-3 mb-3 rounded text-center" style="background: rgba(254, 204, 86, 0.05); border: 1px dashed rgba(254, 204, 86, 0.3);">
                <i class="fas fa-fingerprint fa-2x text-warning mb-2"></i>
                <div class="font-weight-bold text-white small mb-1">{{ __('No Biometric Passkey Registered Yet') }}</div>
                <p class="text-muted small mb-0" style="font-size: 11.5px;">
                    {{ __('Click below on this device (iPhone, iPad, Android phone, MacBook, or Windows Hello PC) to pair your biometric sensor in seconds.') }}
                </p>
            </div>
        @endif

        <!-- Action Button -->
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <button type="button" class="btn font-weight-bold px-4 py-2" id="btnRegisterBiometricPasskey" style="background: linear-gradient(135deg, #fecc56, #f0a500); color: #000; border: none; border-radius: 8px; font-size: 13.5px; box-shadow: 0 4px 14px rgba(254,204,86,0.3);">
                <i class="fas fa-shield-alt mr-1"></i> {{ __('Register This Device (Touch ID / Face ID / Windows Hello)') }}
            </button>
            <span class="text-muted small" style="font-size: 11px;">
                <i class="fas fa-lock text-success mr-1"></i> {{ __('Hardware-backed FIDO2 / WebAuthn protocol.') }}
            </span>
        </div>

        <div id="passkeyRegisterFeedback" class="mt-3 small" style="display: none;"></div>
    </div>
</div>

<script>
(function() {
  const btn = document.getElementById('btnRegisterBiometricPasskey');
  const feedback = document.getElementById('passkeyRegisterFeedback');
  if (!btn) return;

  function setFeedback(msg, isError) {
    if (!feedback) return;
    feedback.style.display = 'block';
    feedback.className = 'mt-3 small p-2 rounded ' + (isError ? 'alert alert-danger' : 'alert alert-success');
    feedback.innerHTML = (isError ? '<i class="fas fa-exclamation-circle mr-1"></i> ' : '<i class="fas fa-check-circle mr-1"></i> ') + msg;
  }

  function base64UrlToUint8Array(str) {
    str = str.replace(/-/g, '+').replace(/_/g, '/');
    while (str.length % 4) str += '=';
    const binary = window.atob(str);
    const bytes = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
      bytes[i] = binary.charCodeAt(i);
    }
    return bytes;
  }

  function getDeviceDefaultName() {
    const ua = navigator.userAgent;
    if (/iPhone/i.test(ua)) return 'Apple iPhone (Touch/Face ID)';
    if (/iPad/i.test(ua)) return 'Apple iPad (Touch/Face ID)';
    if (/Macintosh/i.test(ua)) return 'Apple Mac (Touch ID)';
    if (/Android/i.test(ua)) return 'Android Device Biometrics';
    if (/Windows/i.test(ua)) return 'Windows PC (Windows Hello)';
    return 'Biometric Security Key';
  }

  btn.addEventListener('click', async function() {
    if (!window.PublicKeyCredential) {
      setFeedback('{{ __("WebAuthn Biometrics are not supported by this browser. Please use modern Safari, Chrome, Edge, or Firefox.") }}', true);
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Waiting for Biometric Touch/Scan...") }}';
    feedback.style.display = 'none';

    try {
      const optRes = await fetch("{{ route('auth.passkey.register-options') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      const options = await optRes.json();
      if (!options || options.status !== 'ok') {
        throw new Error(options?.message || 'Could not initiate biometric challenge from server.');
      }

      const challengeBytes = base64UrlToUint8Array(options.challenge);
      const userIdBytes = base64UrlToUint8Array(options.user.id);

      const credential = await navigator.credentials.create({
        publicKey: {
          challenge: challengeBytes,
          rp: options.rp,
          user: {
            id: userIdBytes,
            name: options.user.name,
            displayName: options.user.displayName
          },
          pubKeyCredParams: options.pubKeyCredParams,
          authenticatorSelection: options.authenticatorSelection,
          timeout: options.timeout || 60000
        }
      });

      if (!credential) {
        throw new Error('Biometric authorization was cancelled or failed.');
      }

      const credentialId = credential.id;
      const deviceName = prompt('{{ __("Enter a label for this device:") }}', getDeviceDefaultName()) || getDeviceDefaultName();

      const verifyRes = await fetch("{{ route('auth.passkey.register-verify') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          credential_id: credentialId,
          device_name: deviceName
        })
      });

      const verifyData = await verifyRes.json();
      if (verifyData && verifyData.status === 'ok') {
        setFeedback(verifyData.message || '{{ __("Biometric passkey registered successfully!") }}', false);
        btn.innerHTML = '<i class="fas fa-check mr-1"></i> {{ __("Device Registered!") }}';
        setTimeout(function() {
          window.location.reload();
        }, 1200);
      } else {
        throw new Error(verifyData?.message || 'Could not verify registered passkey on server.');
      }
    } catch(err) {
      console.warn('Biometric registration error:', err);
      let userMsg = err.message || 'Biometric authentication was cancelled or could not be completed.';
      if (err.name === 'NotAllowedError') {
        userMsg = 'Biometric prompt timed out or was dismissed.';
      }
      setFeedback(userMsg, true);
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-shield-alt mr-1"></i> {{ __("Register This Device (Touch ID / Face ID / Windows Hello)") }}';
    }
  });
})();
</script>
