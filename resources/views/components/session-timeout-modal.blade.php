<!-- HIGH-SECURITY SESSION INACTIVITY AUTO-LOCK MODAL (IFW REPLICA) -->
<style>
    .session-timeout-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(4, 6, 10, 0.88);
        backdrop-filter: blur(8px);
        z-index: 10050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .session-timeout-modal {
        background: #181c26;
        border: 1px solid #283244;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.85);
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        animation: modalScaleIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalScaleIn {
        from { opacity: 0; transform: scale(0.94); }
        to { opacity: 1; transform: scale(1); }
    }

    .session-modal-header {
        background: #12151e;
        border-bottom: 1px solid #232b3c;
        padding: 14px 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.8px;
        color: #fecc56;
        text-transform: uppercase;
    }

    .session-modal-body {
        padding: 32px 28px 24px;
        text-align: center;
    }

    .hourglass-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.15);
        border: 1.5px solid rgba(239, 68, 68, 0.4);
        color: #f87171;
        font-size: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        animation: pulseHourglass 2s infinite ease-in-out;
    }

    @keyframes pulseHourglass {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.3); }
        50% { transform: scale(1.05); box-shadow: 0 0 16px rgba(239, 68, 68, 0.3); }
    }

    .timeout-headline {
        font-size: 16px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .timeout-desc {
        font-size: 12.5px;
        color: #94a3b8;
        line-height: 1.5;
        max-width: 380px;
        margin: 0 auto 20px;
    }

    .timeout-timer-box {
        background: #0d1017;
        border: 1.5px solid #ef4444;
        border-radius: 10px;
        padding: 16px 24px;
        font-size: 38px;
        font-weight: 800;
        color: #ef4444;
        letter-spacing: 1px;
        margin-bottom: 20px;
        box-shadow: inset 0 0 18px rgba(239, 68, 68, 0.15);
    }

    .timeout-caption {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 24px;
    }

    .session-modal-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 0 28px 28px;
    }

    .btn-lock-now {
        background: transparent;
        border: 1px solid #ef4444;
        color: #f87171;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-lock-now:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #ffffff;
        border-color: #f87171;
    }

    .btn-stay-logged {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 22px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35);
    }

    .btn-stay-logged:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(249, 115, 22, 0.5);
    }

    /* Unlock PIN Challenge View */
    .pin-unlock-container {
        display: none;
    }

    .pin-unlock-box {
        background: #0d1017;
        border: 1.5px solid #283244;
        border-radius: 10px;
        padding: 12px 18px;
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 12px;
        text-align: center;
        margin: 16px 0;
        width: 100%;
        outline: none;
    }

    .pin-unlock-box:focus {
        border-color: #fecc56;
        box-shadow: 0 0 14px rgba(254, 204, 86, 0.25);
    }
</style>

<div id="sessionTimeoutBackdrop" class="session-timeout-backdrop">
    <div class="session-timeout-modal">
        <!-- Header -->
        <div class="session-modal-header">
            <i class="fas fa-shield-alt"></i>
            <span>{{ __('High-Security Session Timeout') }}</span>
        </div>

        <!-- Warning View (Counting down) -->
        <div id="timeoutWarningView">
            <div class="session-modal-body">
                <div class="hourglass-circle">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="timeout-headline">{{ __('Inactivity Auto-Lock Warning') }}</div>
                <div class="timeout-desc">
                    {{ __('For your protection and cryptographic evidence integrity, this portal session will automatically lock in:') }}
                </div>
                <div class="timeout-timer-box" id="timeoutCountdownDisplay">
                    60 s
                </div>
                <div class="timeout-caption">
                    {{ __('Click below to keep your confidential session active.') }}
                </div>
            </div>

            <div class="session-modal-footer">
                <button type="button" class="btn-lock-now" onclick="triggerSessionLock()">
                    <i class="fas fa-lock"></i> {{ __('Lock Now') }}
                </button>
                <button type="button" class="btn-stay-logged" onclick="resetInactivityTimer()">
                    <i class="fas fa-redo-alt"></i> {{ __('Stay Logged In') }}
                </button>
            </div>
        </div>

        <!-- Session Locked View (PIN & Biometric Challenge) -->
        <div id="timeoutLockedView" class="pin-unlock-container">
            <div class="session-modal-body">
                <div class="hourglass-circle" style="background: rgba(254,204,86,0.15); border-color: rgba(254,204,86,0.4); color: #fecc56;">
                    <i class="fas fa-user-lock"></i>
                </div>
                <div class="timeout-headline">{{ __('Session Locked') }}</div>
                <div class="timeout-desc">
                    {{ __('Your confidential session has been locked due to inactivity. Authenticate via Touch ID / Face ID or enter your 4-digit Security PIN to resume:') }}
                </div>

                <div id="pinUnlockErrorMsg" class="alert alert-danger font-weight-bold py-1 px-2 mb-2" style="display:none; font-size:12px; border-radius:6px;"></div>

                <!-- Biometric WebAuthn Unlock (Touch ID / Face ID / Windows Hello) -->
                @php
                    $activeUserObj = Auth::guard('admin')->user() ?: Auth::user();
                    $hasRegisteredBiometrics = $activeUserObj && !empty($activeUserObj->passkey_credentials) && (is_array($activeUserObj->passkey_credentials) ? count($activeUserObj->passkey_credentials) > 0 : !empty(json_decode($activeUserObj->passkey_credentials, true)));
                @endphp
                <div class="mb-3">
                    <button type="button" class="btn btn-block py-2 font-weight-bold" id="btnTimeoutBiometricUnlock" onclick="unlockSessionWithBiometrics()" style="background: rgba(37, 99, 235, 0.15); color: #60a5fa; border: 1.5px solid rgba(96, 165, 250, 0.4); border-radius: 10px; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                        <i class="fas fa-fingerprint" style="font-size: 17px;"></i>
                        <span>{{ __('Unlock with Touch ID / Face ID') }}</span>
                    </button>
                    <div class="mt-2 text-center" id="sessionBiometricSetupRow" style="{{ $hasRegisteredBiometrics ? 'display: none;' : '' }}">
                        <a href="javascript:void(0)" onclick="quickSetupBiometricFromModal()" class="small text-warning font-weight-bold text-decoration-none" style="font-size: 11.5px;">
                            <i class="fas fa-plus-circle mr-1"></i> {{ __('Register Touch ID / Face ID on This Device Now') }}
                        </a>
                    </div>
                </div>

                <div class="small text-muted mb-2 text-center" style="font-size: 11px;">
                    — {{ __('or enter your 4-digit Security PIN below') }} —
                </div>

                <input type="password" id="sessionUnlockPinInput" maxlength="6" class="pin-unlock-box" placeholder="••••" autocomplete="off">
            </div>

            <div class="session-modal-footer" style="justify-content: center; gap: 14px;">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-lock-now" style="border-color:#374151; color:#94a3b8;">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Sign Out') }}
                    </button>
                </form>
                <button type="button" class="btn-stay-logged" onclick="submitSessionUnlockPin()">
                    <i class="fas fa-unlock"></i> {{ __('Unlock Session') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    "use strict";

    @if(session()->has('impersonator_admin') || session()->has('impersonated_by') || session()->has('admin_login_as_bypass'))
        // Bypassed during admin impersonation session
        return;
    @endif

    var INACTIVITY_LIMIT_MS = 10 * 60 * 1000; // 10 minutes idle triggers warning
    var COUNTDOWN_SECONDS = 60; // 60 seconds warning countdown

    var idleTimer = null;
    var countdownTimer = null;
    var remainingSeconds = COUNTDOWN_SECONDS;

    var backdrop = document.getElementById('sessionTimeoutBackdrop');
    var warningView = document.getElementById('timeoutWarningView');
    var lockedView = document.getElementById('timeoutLockedView');
    var countdownDisplay = document.getElementById('timeoutCountdownDisplay');
    var pinInput = document.getElementById('sessionUnlockPinInput');
    var errorMsg = document.getElementById('pinUnlockErrorMsg');

    function startIdleWatchdog() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarningModal, INACTIVITY_LIMIT_MS);
    }

    function showWarningModal() {
        if (!backdrop) return;
        remainingSeconds = COUNTDOWN_SECONDS;
        updateCountdownDisplay();
        warningView.style.display = 'block';
        lockedView.style.display = 'none';
        backdrop.style.display = 'flex';

        clearInterval(countdownTimer);
        countdownTimer = setInterval(function() {
            remainingSeconds--;
            updateCountdownDisplay();
            if (remainingSeconds <= 0) {
                clearInterval(countdownTimer);
                triggerSessionLock();
            }
        }, 1000);
    }

    function updateCountdownDisplay() {
        if (countdownDisplay) {
            countdownDisplay.textContent = remainingSeconds + ' s';
        }
    }

    window.resetInactivityTimer = function() {
        clearInterval(countdownTimer);
        if (backdrop) backdrop.style.display = 'none';
        startIdleWatchdog();
    };

    window.triggerSessionLock = function() {
        clearInterval(countdownTimer);
        if (warningView) warningView.style.display = 'none';
        if (lockedView) lockedView.style.display = 'block';
        if (backdrop) backdrop.style.display = 'flex';
        if (localStorage.getItem('biometric_device_registered') === 'true') {
            var setupRow = document.getElementById('sessionBiometricSetupRow');
            if (setupRow) setupRow.style.display = 'none';
        }
        if (pinInput) {
            pinInput.value = '';
            setTimeout(function() { pinInput.focus(); }, 150);
        }

        // Auto-check session validity immediately on lock
        fetch("{{ route('client.security.check-session') }}", {
            headers: { 'Accept': 'application/json' }
        })
        .then(async function(r) {
            if (r.headers.get('content-type')?.includes('application/json')) {
                return await r.json();
            }
            return null;
        })
        .then(function(state) {
            if (state && (state.status === 'expired' || state.authenticated === false)) {
                showUnlockError("{{ __('Your session has expired due to inactivity. Redirecting to sign in...') }}");
                setTimeout(function() {
                    window.location.href = (state.redirect || "{{ route('login') }}") + '?expired=1';
                }, 1500);
            }
        }).catch(function() {});

        // Notify backend session locked
        fetch("{{ route('client.security.lock-session') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            }
        }).catch(function(){});
    };

    function base64UrlToBytes(str) {
        if (!str) return new Uint8Array(0);
        var s = str.replace(/-/g, '+').replace(/_/g, '/');
        while (s.length % 4 !== 0) {
            s += '=';
        }
        var binary = atob(s);
        var bytes = new Uint8Array(binary.length);
        for (var i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }
        return bytes;
    }

    window.submitSessionUnlockPin = function() {
        var pin = pinInput ? pinInput.value.trim() : '';
        if (!pin) {
            showUnlockError("{{ __('Please enter your Security PIN.') }}");
            return;
        }

        fetch("{{ route('client.security.unlock-session') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ pin: pin })
        })
        .then(async function(res) {
            var data = null;
            try {
                if (res.headers.get('content-type')?.includes('application/json')) {
                    data = await res.json();
                }
            } catch(e) {}
            if (!res.ok) {
                if (res.status === 401 || res.status === 419 || (data && data.status === 'expired')) {
                    showUnlockError("{{ __('Your session has expired due to inactivity. Redirecting to login...') }}");
                    var redir = (data && data.redirect) ? data.redirect : "{{ route('login') }}";
                    setTimeout(function() {
                        window.location.href = redir + (redir.indexOf('?') >= 0 ? '&' : '?') + 'expired=1';
                    }, 1200);
                    return null;
                }
                if (res.status >= 500) {
                    throw new Error("{{ __('The security server timed out or is temporarily busy. Please try again.') }}");
                }
                throw new Error((data && data.message) ? data.message : "{{ __('Invalid Security PIN. Please try again.') }}");
            }
            return data;
        })
        .then(function(data) {
            if (!data) return;
            if (data.status === 'expired') {
                var redir = data.redirect || "{{ route('login') }}";
                window.location.href = redir + (redir.indexOf('?') >= 0 ? '&' : '?') + 'expired=1';
                return;
            }
            if (errorMsg) errorMsg.style.display = 'none';
            resetInactivityTimer();
        })
        .catch(function(err) {
            showUnlockError(err.message || "{{ __('Invalid Security PIN. Please try again.') }}");
        });
    };

    window.unlockSessionWithBiometrics = async function() {
        if (!window.PublicKeyCredential) {
            showUnlockError("{{ __('Biometric authentication is not supported on this browser.') }}");
            return;
        }

        var btn = document.getElementById('btnTimeoutBiometricUnlock');
        var origText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Scanning Touch ID / Face ID...") }}';
        }

        try {
            var optRes = await fetch("{{ route('auth.passkey.auth-options') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (!optRes.headers.get('content-type')?.includes('application/json')) {
                showUnlockError("{{ __('Your session has expired due to inactivity. Redirecting to login...') }}");
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}?expired=1";
                }, 1200);
                return;
            }
            var options = await optRes.json();

            if (!options || !options.has_passkeys) {
                var doRegister = confirm("{{ __('No biometric passkey registered on this device yet. Would you like to register Touch ID / Face ID on this device now?') }}");
                if (doRegister) {
                    await window.quickSetupBiometricFromModal();
                } else {
                    showUnlockError("{{ __('Please enter your 4-digit PIN to resume.') }}");
                }
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }
                return;
            }

            var challengeBuffer = base64UrlToBytes(options.challenge);
            var allowList = (options.allowCredentials || []).map(function(c) {
                return {
                    type: 'public-key',
                    id: base64UrlToBytes(c.id)
                };
            });

            var assertion = await navigator.credentials.get({
                publicKey: {
                    challenge: challengeBuffer,
                    rpId: window.location.hostname,
                    allowCredentials: allowList,
                    userVerification: 'preferred',
                    timeout: 60000
                }
            });

            var credId = btoa(String.fromCharCode.apply(null, new Uint8Array(assertion.rawId)))
                .replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');

            var verifyRes = await fetch("{{ route('auth.passkey.auth-verify') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ credential_id: credId })
            });

            if (!verifyRes.headers.get('content-type')?.includes('application/json')) {
                showUnlockError("{{ __('Your session has expired due to inactivity. Redirecting to login...') }}");
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}?expired=1";
                }, 1200);
                return;
            }

            var vData = await verifyRes.json();
            if (verifyRes.status === 401 || (vData && vData.status === 'expired')) {
                var redir = (vData && vData.redirect) ? vData.redirect : "{{ route('login') }}";
                window.location.href = redir + (redir.indexOf('?') >= 0 ? '&' : '?') + 'expired=1';
                return;
            }
            if (vData.status === 'ok' || vData.status === 'success') {
                if (errorMsg) errorMsg.style.display = 'none';
                resetInactivityTimer();
            } else {
                showUnlockError(vData.message || "{{ __('Biometric unlock failed. Please use PIN.') }}");
            }
        } catch(e) {
            if (e.name !== 'NotAllowedError') {
                showUnlockError(e.message || "{{ __('Biometric authentication cancelled or unavailable.') }}");
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origText;
            }
        }
    };

    window.quickSetupBiometricFromModal = async function() {
        if (!window.PublicKeyCredential) {
            alert("{{ __('Biometric authentication is not supported by this browser.') }}");
            return;
        }

        try {
            var optRes = await fetch("{{ route('auth.passkey.register-options') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!optRes.headers.get('content-type')?.includes('application/json')) {
                showUnlockError("{{ __('Your session has expired due to inactivity. Redirecting to login...') }}");
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}?expired=1";
                }, 1200);
                return;
            }
            var options = await optRes.json();
            if (!options || options.status !== 'ok') {
                throw new Error(options?.message || 'Could not initiate biometric challenge');
            }

            var challengeBytes = base64UrlToBytes(options.challenge);
            var userIdBytes = base64UrlToBytes(options.user.id);

            var credential = await navigator.credentials.create({
                publicKey: {
                    challenge: challengeBytes,
                    rp: { name: options.rp.name || 'Your CPA Expert', id: window.location.hostname },
                    user: {
                        id: userIdBytes,
                        name: options.user.name,
                        displayName: options.user.displayName
                    },
                    pubKeyCredParams: options.pubKeyCredParams || [
                        { alg: -7, type: 'public-key' },
                        { alg: -257, type: 'public-key' }
                    ],
                    authenticatorSelection: {
                        userVerification: 'preferred',
                        residentKey: 'preferred',
                        requireResidentKey: false
                    },
                    timeout: 60000
                }
            });

            if (!credential) throw new Error('Biometric registration was cancelled.');

            var credId = btoa(String.fromCharCode.apply(null, new Uint8Array(credential.rawId)))
                .replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');

            var devName = (/iPhone|iPad|Macintosh/i.test(navigator.userAgent) ? 'Apple Device (Touch/Face ID)' : (/Android/i.test(navigator.userAgent) ? 'Android Biometrics' : 'Windows Hello Device'));

            var saveRes = await fetch("{{ route('auth.passkey.register-verify') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    credential_id: credId,
                    device_name: devName
                })
            });

            var sData = await saveRes.json();
            if (sData && (sData.status === 'ok' || sData.status === 'success')) {
                localStorage.setItem('biometric_device_registered', 'true');
                var setupRow = document.getElementById('sessionBiometricSetupRow');
                if (setupRow) setupRow.style.display = 'none';
                alert("{{ __('Touch ID / Face ID registered successfully on this device! Session unlocked.') }}");
                if (errorMsg) errorMsg.style.display = 'none';
                resetInactivityTimer();
            } else {
                throw new Error(sData?.message || 'Registration failed');
            }
        } catch(err) {
            if (err.name !== 'NotAllowedError') {
                showUnlockError(err.message || "{{ __('Could not register biometrics on this device.') }}");
            }
        }
    };

    function showUnlockError(msg) {
        if (errorMsg) {
            errorMsg.textContent = msg;
            errorMsg.style.display = 'block';
        }
    }

    if (pinInput) {
        pinInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                submitSessionUnlockPin();
            }
        });
    }

    // Reset idle timer on active user events (when not modal locked)
    var activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart'];
    activityEvents.forEach(function(evt) {
        window.addEventListener(evt, function() {
            if (!backdrop || backdrop.style.display !== 'flex') {
                startIdleWatchdog();
            }
        }, { passive: true });
    });

    // Check if session was already locked server-side on initial page load
    var isServerLocked = {{ session('session_locked') ? 'true' : 'false' }};
    if (isServerLocked) {
        if (warningView) warningView.style.display = 'none';
        if (lockedView) lockedView.style.display = 'block';
        if (backdrop) backdrop.style.display = 'flex';
        if (pinInput) {
            setTimeout(function() { pinInput.focus(); }, 200);
        }
    } else {
        startIdleWatchdog();
    }

    // Expose preview function for testing
    window.testSessionTimeoutWarning = function() {
        showWarningModal();
    };
})();
</script>
