<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Your CPA Expert') }} | {{ __('Client Verification') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --bg-page: #0a0c10;
            --card-bg: #11151e;
            --card-border: #1e2533;
            --primary-gold: #fecc56;
            --gold-gradient: linear-gradient(135deg, #fecc56, #f0a500);
            --key-bg: #171c28;
            --key-border: #262f40;
            --key-hover: #222a3a;
            --text-main: #f1f5f9;
            --text-muted: #8492a6;
        }

        * {
            box-sizing: border-box;
            user-select: none;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-main);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            margin: 0;
        }

        .auth-gate-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: 0 16px 50px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 440px;
            padding: 32px 28px;
            text-align: center;
            position: relative;
            animation: cardAppear 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Shield Icon */
        .shield-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(254, 204, 86, 0.08);
            border: 2px solid var(--primary-gold);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-gold);
            font-size: 26px;
            margin-bottom: 16px;
            box-shadow: 0 0 24px rgba(254, 204, 86, 0.25);
        }

        .gate-title {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .gate-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            margin-bottom: 22px;
        }

        .gate-subtitle strong {
            color: #e2e8f0;
        }

        /* Tabs Container */
        .auth-tab-switch {
            display: flex;
            background: #0d1017;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 22px;
            gap: 4px;
        }

        .tab-switch-btn {
            flex: 1;
            padding: 9px 12px;
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            background: transparent;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all 0.2s ease;
        }

        .tab-switch-btn.active {
            background: var(--gold-gradient);
            color: #000000;
            box-shadow: 0 2px 10px rgba(254, 204, 86, 0.3);
        }

        .tab-switch-btn:not(.active):hover {
            color: #cbd5e1;
            background: rgba(255, 255, 255, 0.04);
        }

        /* Header label row */
        .input-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            font-size: 12.5px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .toggle-visibility-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 2px 6px;
            border-radius: 4px;
            transition: color 0.15s;
        }

        .toggle-visibility-btn:hover {
            color: var(--primary-gold);
        }

        /* OTP / PIN Code Digits Boxes */
        .digits-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 22px;
        }

        .digit-box {
            width: 48px;
            height: 54px;
            background: #0c0f16;
            border: 2px solid var(--key-border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .digit-box.filled {
            border-color: #3b455b;
            background: #141822;
        }

        .digit-box.active {
            border-color: var(--primary-gold);
            box-shadow: 0 0 14px rgba(254, 204, 86, 0.35);
            background: #171c26;
        }

        .digit-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ffffff;
            display: inline-block;
        }

        /* Interactive Numeric Keypad */
        .numpad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            margin-bottom: 20px;
        }

        .num-key {
            height: 52px;
            background: var(--key-bg);
            border: 1px solid var(--key-border);
            border-radius: 10px;
            color: #f1f5f9;
            font-size: 19px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.12s ease;
            outline: none;
        }

        .num-key:hover {
            background: var(--key-hover);
            border-color: #3b465c;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .num-key:active {
            transform: scale(0.96);
            background: #2a3346;
            border-color: var(--primary-gold);
        }

        .num-key.utility {
            font-size: 16px;
            color: var(--text-muted);
        }

        /* Action Buttons */
        .btn-verify-submit {
            background: var(--gold-gradient);
            color: #000000;
            font-weight: 700;
            font-size: 14.5px;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 18px rgba(254, 204, 86, 0.25);
            letter-spacing: 0.3px;
        }

        .btn-verify-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(254, 204, 86, 0.4);
            color: #000000;
        }

        .btn-verify-submit:active {
            transform: scale(0.98);
        }

        /* Footer Links */
        .resend-link-row {
            margin-top: 18px;
            margin-bottom: 12px;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #fecc56;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.15s;
            text-decoration: none;
        }

        .resend-btn:hover {
            opacity: 0.85;
            color: #fed878;
        }

        .return-login-link {
            color: var(--text-muted);
            font-size: 12.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s;
            margin-top: 4px;
        }

        .return-login-link:hover {
            color: #e2e8f0;
            text-decoration: none;
        }

        /* Toast / Alert Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #181d28;
            border: 1px solid var(--primary-gold);
            color: #f1f5f9;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 8px 30px rgba(0,0,0,0.6);
            z-index: 9999;
            display: none;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div id="toastNotification" class="toast-notification">
    <i class="fas fa-check-circle text-warning"></i>
    <span id="toastMessage">{{ __('Verification code sent!') }}</span>
</div>

<div class="auth-gate-card">
    <!-- Top Shield Icon -->
    <div class="shield-icon-wrapper">
        <i class="fas fa-shield-alt"></i>
    </div>

    <!-- Title & Welcome -->
    <h2 class="gate-title">{{ __('Client Verification') }}</h2>
    <div class="gate-subtitle">
        {{ __('Welcome back,') }} <strong>{{ Auth::user()->name }}</strong>
    </div>

    <!-- Flash Alerts -->
    @if(session('error'))
        <div class="alert alert-danger font-weight-bold mb-3 py-2 px-3 text-left" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); font-size: 12.5px; border-radius: 8px;">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success font-weight-bold mb-3 py-2 px-3 text-left" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); font-size: 12.5px; border-radius: 8px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Tab Switcher (Email Code / Security PIN) -->
    <div class="auth-tab-switch">
        <button type="button" class="tab-switch-btn active" id="tabEmailBtn" onclick="switchTab('email')">
            <i class="fas fa-envelope"></i> {{ __('Email Code') }}
        </button>
        <button type="button" class="tab-switch-btn" id="tabPinBtn" onclick="switchTab('pin')">
            <i class="fas fa-key"></i> {{ __('Security PIN') }}
        </button>
    </div>

    <!-- MAIN FORM -->
    <form id="verifyOtpForm" action="{{ route('client.security.verify-otp.check') }}" method="POST">
        @csrf
        <input type="hidden" name="auth_method" id="authMethodInput" value="email_code">
        <input type="hidden" name="otp" id="rawOtpValue" value="">
        <input type="hidden" name="pin" id="rawPinValue" value="">

        <!-- Top Row: Label & Toggle Show Digits -->
        <div class="input-label-row">
            <span id="inputSectionLabel">{{ __('Enter 6-Digit Email Code') }}</span>
            <button type="button" class="toggle-visibility-btn" id="toggleShowDigitsBtn" onclick="toggleDigitVisibility()">
                <i class="fas fa-eye" id="toggleIcon"></i>
                <span id="toggleText">{{ __('Show Digits') }}</span>
            </button>
        </div>

        <!-- Digits Boxes Container -->
        <div class="digits-container" id="digitsBoxesContainer">
            <!-- 6 Boxes dynamically handled by JS -->
        </div>

        <!-- Numeric Keypad -->
        <div class="numpad-grid">
            <button type="button" class="num-key" onclick="pressKey('1')">1</button>
            <button type="button" class="num-key" onclick="pressKey('2')">2</button>
            <button type="button" class="num-key" onclick="pressKey('3')">3</button>
            <button type="button" class="num-key" onclick="pressKey('4')">4</button>
            <button type="button" class="num-key" onclick="pressKey('5')">5</button>
            <button type="button" class="num-key" onclick="pressKey('6')">6</button>
            <button type="button" class="num-key" onclick="pressKey('7')">7</button>
            <button type="button" class="num-key" onclick="pressKey('8')">8</button>
            <button type="button" class="num-key" onclick="pressKey('9')">9</button>
            <button type="button" class="num-key utility" onclick="clearAllDigits()" title="{{ __('Clear Digits') }}">
                <i class="fas fa-undo"></i>
            </button>
            <button type="button" class="num-key" onclick="pressKey('0')">0</button>
            <button type="button" class="num-key utility" onclick="backspaceDigit()" title="{{ __('Backspace') }}">
                <i class="fas fa-backspace"></i>
            </button>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-verify-submit" id="submitBtn">
            {{ __('Verify OTP & Proceed') }}
        </button>
    </form>

    <!-- Resend Code (Only on Email Tab) -->
    <div class="resend-link-row" id="resendRow">
        <button type="button" class="resend-btn" id="resendCodeBtn" onclick="resendVerificationCode()">
            <i class="fas fa-redo-alt" id="resendSpinIcon"></i>
            <span>{{ __('Resend Verification Code') }}</span>
        </button>
    </div>

    <!-- Return to Client Login -->
    <div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="return-login-link bg-transparent border-0 p-0" style="cursor: pointer;">
                <i class="fas fa-arrow-left"></i> {{ __('Return to Client Login') }}
            </button>
        </form>
    </div>
</div>

<!-- Hidden input to capture physical keyboard input easily -->
<input type="text" id="hiddenKeyboardCatcher" style="position: absolute; opacity: 0; pointer-events: none;" autocomplete="off">

<script>
(function() {
    "use strict";

    var currentTab = 'email'; // 'email' (6 digits) or 'pin' (4 digits)
    var enteredDigits = '';
    var showDigits = false;

    var maxDigits = 6;

    var boxesContainer = document.getElementById('digitsBoxesContainer');
    var labelElem = document.getElementById('inputSectionLabel');
    var submitBtn = document.getElementById('submitBtn');
    var resendRow = document.getElementById('resendRow');
    var authMethodInput = document.getElementById('authMethodInput');
    var rawOtpInput = document.getElementById('rawOtpValue');
    var rawPinInput = document.getElementById('rawPinValue');
    var hiddenCatcher = document.getElementById('hiddenKeyboardCatcher');

    function renderBoxes() {
        boxesContainer.innerHTML = '';
        for (var i = 0; i < maxDigits; i++) {
            var box = document.createElement('div');
            box.className = 'digit-box';

            if (i === enteredDigits.length) {
                box.classList.add('active');
            }

            if (i < enteredDigits.length) {
                box.classList.add('filled');
                if (showDigits) {
                    box.textContent = enteredDigits[i];
                } else {
                    var dot = document.createElement('span');
                    dot.className = 'digit-dot';
                    box.appendChild(dot);
                }
            }

            boxesContainer.appendChild(box);
        }

        // Sync hidden inputs
        if (currentTab === 'email') {
            rawOtpInput.value = enteredDigits;
            rawPinInput.value = '';
        } else {
            rawPinInput.value = enteredDigits;
            rawOtpInput.value = '';
        }
    }

    window.switchTab = function(tab) {
        currentTab = tab;
        enteredDigits = '';

        var emailBtn = document.getElementById('tabEmailBtn');
        var pinBtn = document.getElementById('tabPinBtn');

        if (tab === 'email') {
            maxDigits = 6;
            emailBtn.classList.add('active');
            pinBtn.classList.remove('active');
            labelElem.textContent = "{{ __('Enter 6-Digit Email Code') }}";
            submitBtn.textContent = "{{ __('Verify OTP & Proceed') }}";
            resendRow.style.display = 'block';
            authMethodInput.value = 'email_code';
        } else {
            maxDigits = 4;
            pinBtn.classList.add('active');
            emailBtn.classList.remove('active');
            labelElem.textContent = "{{ __('Enter 4-Digit Security PIN') }}";
            submitBtn.textContent = "{{ __('Verify PIN & Proceed') }}";
            resendRow.style.display = 'none';
            authMethodInput.value = 'pin';
        }

        renderBoxes();
        focusCatcher();
    };

    window.toggleDigitVisibility = function() {
        showDigits = !showDigits;
        var toggleIcon = document.getElementById('toggleIcon');
        var toggleText = document.getElementById('toggleText');

        if (showDigits) {
            toggleIcon.className = 'fas fa-eye-slash';
            toggleText.textContent = "{{ __('Hide Digits') }}";
        } else {
            toggleIcon.className = 'fas fa-eye';
            toggleText.textContent = "{{ __('Show Digits') }}";
        }

        renderBoxes();
    };

    window.pressKey = function(digit) {
        if (enteredDigits.length < maxDigits) {
            enteredDigits += digit;
            renderBoxes();

            // Auto submit when full
            if (enteredDigits.length === maxDigits) {
                setTimeout(function() {
                    document.getElementById('verifyOtpForm').submit();
                }, 200);
            }
        }
    };

    window.backspaceDigit = function() {
        if (enteredDigits.length > 0) {
            enteredDigits = enteredDigits.slice(0, -1);
            renderBoxes();
        }
    };

    window.clearAllDigits = function() {
        enteredDigits = '';
        renderBoxes();
    };

    window.resendVerificationCode = function() {
        var spin = document.getElementById('resendSpinIcon');
        var btn = document.getElementById('resendCodeBtn');
        if (spin) spin.classList.add('fa-spin');
        btn.disabled = true;

        fetch("{{ route('client.security.verify-otp.resend') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (spin) spin.classList.remove('fa-spin');
            btn.disabled = false;
            showToast(data.message || "{{ __('A new verification code was sent to your email.') }}");
        })
        .catch(function(err) {
            if (spin) spin.classList.remove('fa-spin');
            btn.disabled = false;
            showToast("{{ __('Code dispatched. Please check your inbox.') }}");
        });
    };

    function showToast(msg) {
        var toast = document.getElementById('toastNotification');
        var text = document.getElementById('toastMessage');
        if (text) text.textContent = msg;
        if (toast) {
            toast.style.display = 'inline-flex';
            setTimeout(function() { toast.style.display = 'none'; }, 4500);
        }
    }

    function focusCatcher() {
        if (hiddenCatcher) hiddenCatcher.focus();
    }

    // Physical Keyboard Listener
    document.addEventListener('keydown', function(e) {
        if (e.key >= '0' && e.key <= '9') {
            pressKey(e.key);
        } else if (e.key === 'Backspace') {
            backspaceDigit();
        } else if (e.key === 'Enter') {
            if (enteredDigits.length === maxDigits) {
                document.getElementById('verifyOtpForm').submit();
            }
        }
    });

    // Initial render
    switchTab('email');
})();
</script>

</body>
</html>
