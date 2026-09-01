<div class="card card-dark shadow-sm rounded mb-4" style="background: #161a23; border: 1px solid #28303f; color: #f1f5f9;">
    <div class="card-header border-bottom" style="background: #1f2533; border-color: #28303f !important;">
        <h5 class="card-title font-weight-bold text-white mb-0" style="font-size: 14px;">
            <i class="fas fa-shield-alt text-warning mr-2"></i> {{ __('Confidential 4-Digit Security PIN') }}
        </h5>
        <small class="text-muted">{{ __('Your 4-digit PIN is required to authorize privileged disbursements, sign settlements, and confirm financial operations.') }}</small>
    </div>
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success font-weight-bold mb-3" style="background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger font-weight-bold mb-3" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171;">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('client.security.set-pin') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="current_password" class="font-weight-bold text-light small">{{ __('Current Account Password') }} <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" id="current_password" class="form-control" style="background: #0f172a; border: 1px solid #334155; color: #ffffff;" required placeholder="••••••••">
                    @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label for="pin" class="font-weight-bold text-light small">{{ __('New 4-Digit Security PIN') }} <span class="text-danger">*</span></label>
                    <input type="password" name="pin" id="pin" maxlength="4" class="form-control" style="background: #0f172a; border: 1px solid #334155; color: #fecc56; letter-spacing: 6px; font-size: 18px;" required placeholder="••••" inputmode="numeric">
                    @error('pin') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label for="pin_confirmation" class="font-weight-bold text-light small">{{ __('Confirm 4-Digit PIN') }} <span class="text-danger">*</span></label>
                    <input type="password" name="pin_confirmation" id="pin_confirmation" maxlength="4" class="form-control" style="background: #0f172a; border: 1px solid #334155; color: #fecc56; letter-spacing: 6px; font-size: 18px;" required placeholder="••••" inputmode="numeric">
                </div>
            </div>

            <div class="text-right mt-2">
                <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4 py-2 shadow-sm" style="background: linear-gradient(135deg, #fecc56, #f0a500); border: none;">
                    <i class="fas fa-key mr-1"></i> {{ __('Update Security PIN') }}
                </button>
            </div>
        </form>
    </div>
</div>
