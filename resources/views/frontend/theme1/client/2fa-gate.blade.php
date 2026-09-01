<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Your CPA Expert') }} | {{ __('2FA Security Access Gate') }}</title>
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
            --bg-color: #0b0e14;
            --card-bg: #161a23;
            --border-color: #28303f;
            --primary-gold: #fecc56;
            --gold-gradient: linear-gradient(135deg, #fecc56, #f0a500);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .gate-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gate-header {
            background: linear-gradient(180deg, #1c2230 0%, #161a23 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 30px 24px;
            text-align: center;
            position: relative;
        }

        .gate-icon {
            width: 68px;
            height: 68px;
            background: rgba(254, 204, 86, 0.12);
            border: 2px solid var(--primary-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--primary-gold);
            font-size: 28px;
            box-shadow: 0 0 20px rgba(254, 204, 86, 0.3);
        }

        .pin-input {
            background: #0f131a !important;
            border: 2px solid #374151 !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            font-size: 28px !important;
            letter-spacing: 14px;
            text-align: center;
            padding: 14px 20px !important;
            font-weight: 800;
            transition: all 0.2s ease;
        }

        .pin-input:focus {
            border-color: var(--primary-gold) !important;
            box-shadow: 0 0 0 3px rgba(254, 204, 86, 0.25) !important;
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: #000 !important;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(254, 204, 86, 0.4);
        }
    </style>
</head>
<body>

<div class="gate-card">
    <div class="gate-header">
        <div class="gate-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <h4 class="font-weight-bold text-white mb-1" style="font-family: 'Outfit', sans-serif;">{{ __('Security Access Gate') }}</h4>
        <p class="text-muted small mb-0">{{ __('Two-Factor Identity & Location Verification') }}</p>
    </div>

    <div class="p-4">
        @if(session('error'))
            <div class="alert alert-danger font-weight-bold mb-4" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); font-size: 13px;">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success font-weight-bold mb-4" style="background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); font-size: 13px;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <div class="small text-muted mb-1">{{ __('Signed in as:') }}</div>
            <strong class="text-white">{{ Auth::user()->name }}</strong>
            <small class="text-muted d-block">{{ Auth::user()->email }}</small>
        </div>

        <form action="{{ route('client.security.2fa-gate.verify') }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label class="small font-weight-bold text-warning text-center d-block mb-2">
                    <i class="fas fa-key mr-1"></i> {{ __('Enter 4-Digit Security PIN') }}
                </label>
                <input type="password" name="pin" maxlength="4" pattern="[0-9]{4}" class="form-control pin-input" placeholder="••••" required autofocus autocomplete="off">
                <small class="text-muted text-center d-block mt-2" style="font-size: 11px;">
                    <i class="fas fa-lock text-success mr-1"></i> {{ __('Encrypted End-to-End') }} &bull; {{ __('IP Location Logged') }}
                </small>
            </div>

            <button type="submit" class="btn btn-gold mb-3">
                <i class="fas fa-unlock-alt mr-2"></i> {{ __('Authorize Session & Enter') }}
            </button>
        </form>

        <div class="pt-3 border-top text-center" style="border-color: #28303f !important;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-muted btn-sm text-decoration-none">
                    <i class="fas fa-sign-out-alt mr-1"></i> {{ __('Cancel & Sign Out') }}
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
