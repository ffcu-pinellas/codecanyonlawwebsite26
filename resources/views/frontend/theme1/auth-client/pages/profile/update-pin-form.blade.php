<div class="card card-dark shadow-sm rounded mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
    <div class="card-header bg-light border-bottom">
        <h5 class="card-title font-weight-bold text-dark mb-0">
            <i class="fas fa-shield-alt text-warning mr-2"></i> {{ __('Confidential 4-Digit Security PIN') }}
        </h5>
        <small class="text-muted">{{ __('Your 4-digit PIN is required to authorize privileged disbursements, sign settlements, and confirm financial operations.') }}</small>
    </div>
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success font-weight-bold mb-3">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger font-weight-bold mb-3">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('client.security.set-pin') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="current_password" class="font-weight-bold text-dark small">{{ __('Current Account Password') }} <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required placeholder="••••••••">
                    @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label for="pin" class="font-weight-bold text-dark small">{{ __('New 4-Digit Security PIN') }} <span class="text-danger">*</span></label>
                    <input type="password" name="pin" id="pin" maxlength="4" class="form-control" required placeholder="••••" inputmode="numeric" style="letter-spacing: 6px; font-size: 18px;">
                    @error('pin') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label for="pin_confirmation" class="font-weight-bold text-dark small">{{ __('Confirm 4-Digit PIN') }} <span class="text-danger">*</span></label>
                    <input type="password" name="pin_confirmation" id="pin_confirmation" maxlength="4" class="form-control" required placeholder="••••" inputmode="numeric" style="letter-spacing: 6px; font-size: 18px;">
                </div>
            </div>

            <div class="text-right mt-2">
                <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4 py-2 shadow-sm">
                    <i class="fas fa-key mr-1"></i> {{ __('Update Security PIN') }}
                </button>
            </div>
        </form>
    </div>
</div>
