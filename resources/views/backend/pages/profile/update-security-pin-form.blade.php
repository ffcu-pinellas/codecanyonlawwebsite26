<div class="col-12">
    <div class="card card-dark bg-dark py-1 border-secondary">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    @php
                        $user = Auth::user();
                        $hasPin = !empty($user->pin_hash);
                    @endphp

                    <div class="mb-3 d-flex align-items-center flex-wrap" style="gap: 10px;">
                        <span class="badge {{ $hasPin ? 'badge-success' : 'badge-warning' }} px-3 py-2 font-weight-bold" style="font-size: 13px;">
                            <i class="fas {{ $hasPin ? 'fa-shield-alt' : 'fa-exclamation-triangle' }} mr-1"></i>
                            {{ $hasPin ? __('4-Digit Security PIN: Active & Configured') : __('4-Digit Security PIN: Not Set') }}
                        </span>
                        <span class="badge badge-info px-3 py-2 font-weight-bold" style="font-size: 13px;">
                            <i class="fab fa-telegram-plane mr-1"></i>
                            {{ __('Telegram Remote Control: Active') }}
                        </span>
                    </div>

                    <form action="{{ route('admin.security.set-pin') }}" method="post" class="wma-form">
                        @csrf
                        <div class="col-12 px-0">
                            <div class="row mt-2">
                                <div class="col-12">
                                    <p class="mb-1 font-weight-bold text-left">
                                        <label for="adminPin" class="text-white">{{ __('New 4-Digit Security PIN') }}:</label>
                                    </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" name="pin" id="adminPin" maxlength="4" pattern="[0-9]{4}" inputmode="numeric"
                                               class="form-control rounded bg-dark text-white border-secondary"
                                               placeholder="••••" required autocomplete="off">
                                        @if ($errors->has('pin'))
                                            <span class="text-danger w-100 d-block mt-1">{{ $errors->first('pin') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold text-left">
                                        <label for="adminPinConfirmation" class="text-white">{{ __('Confirm 4-Digit PIN') }}:</label>
                                    </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" name="pin_confirmation" id="adminPinConfirmation" maxlength="4" pattern="[0-9]{4}" inputmode="numeric"
                                               class="form-control rounded bg-dark text-white border-secondary"
                                               placeholder="••••" required autocomplete="off">
                                        @if ($errors->has('pin_confirmation'))
                                            <span class="text-danger w-100 d-block mt-1">{{ $errors->first('pin_confirmation') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold text-left">
                                        <label for="adminPinPassword" class="text-white">{{ __('Current Password (Verification)') }}:</label>
                                    </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" name="current_password" id="adminPinPassword"
                                               class="form-control rounded bg-dark text-white border-secondary"
                                               placeholder="{{ __('Enter current admin password') }}">
                                        @if ($errors->has('current_password'))
                                            <span class="text-danger w-100 d-block mt-1">{{ $errors->first('current_password') }}</span>
                                        @endif
                                    </div>

                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input" id="enable2faAdmin" name="enable_2fa" value="1" {{ $user->two_factor_enabled ? 'checked' : '' }}>
                                        <label class="custom-control-label text-light font-weight-bold" for="enable2faAdmin">
                                            {{ __('Enforce 2FA Security Gate Challenge on Sign-in') }}
                                        </label>
                                        <small class="form-text text-muted">
                                            {{ __('When enabled, you can enter your 4-digit PIN directly to pass the security gate without waiting for email OTP codes.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-left">
                                <button class="btn btn-warning font-weight-bold text-dark px-4 py-2 shadow-sm" type="submit">
                                    <i class="fas fa-key mr-1"></i> {{ __('Save Security Gate PIN') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 py-4 text-left">
                    <div class="h5 text-warning font-weight-bold mb-2">
                        <i class="fas fa-user-shield mr-2"></i>{{ __('Admin 2FA Gate PIN') }}
                    </div>
                    <p class="text-muted small mb-3">
                        {{ __('Set your permanent 4-digit access PIN. This allows you to instantly unlock the Security Gate on new browsers or devices without needing email OTP confirmation.') }}
                    </p>
                    <div class="p-3 rounded" style="background: rgba(254, 204, 86, 0.08); border: 1px dashed rgba(254, 204, 86, 0.35);">
                        <div class="font-weight-bold text-warning small mb-1">
                            <i class="fab fa-telegram mr-1"></i> {{ __('Telegram Quick Command') }}
                        </div>
                        <p class="text-muted small mb-1">
                            {{ __('You can also update your PIN anytime via your connected Telegram bot by sending:') }}
                        </p>
                        <code class="text-warning font-weight-bold" style="font-size: 13px;">/setpin 1234</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

