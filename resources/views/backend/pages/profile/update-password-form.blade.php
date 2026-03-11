<div class="col-12">
    <div class="card card-dark bg-dark py-1">

        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('admin.password-update') }}" method="post" enctype="multipart/form-data" class="wma-form">
                        @csrf
                        <div class="col-12">
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p class="mb-1 font-weight-bold text-right"><label for="currentPassword">{{ __('Current Password') }}: </label> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" name="current_password" id="currentPassword" class="form-control rounded text-right"
                                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                                        <br>
                                        @if ($errors->has('current_password'))
                                            <span class="text-danger">{{ $errors->first('current_password') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold text-right"><label for="password">{{ __('New Password') }}:</label> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" name="password" id="password" class="form-control rounded text-right"
                                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                                        <br>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold text-right"><label for="passwordConfirmation">{{ __('Confirm Password') }}:</label> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" name="password_confirmation" id="passwordConfirmation" class="form-control rounded text-right"
                                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                                        <br>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="float-left">
                                <button class="btn btn-wave-light rounded btn-danger btn-lg" type="submit">{{ __('Save') }}</button>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-md-4 py-5 text-right">
                    <div class="h5">{{ __('Update Password') }}</div>
                    <p class="">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
