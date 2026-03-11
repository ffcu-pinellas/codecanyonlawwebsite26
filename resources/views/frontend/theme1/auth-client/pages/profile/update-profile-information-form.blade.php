<div class="col-12 mb-3">
    <div class="card py-1">

        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4 py-5">
                    <div class="h5">{{ __('Profile Information') }}</div>
                    <p class="">{{ __('Update your account profile information.') }}</p>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('client.profile-update') }}" method="post" enctype="multipart/form-data" class="wma-form">
                        @csrf
                        <div class="">
                            <p class="h6 mb-3">{{ __('Photo') }}</p>
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <div class="admin-image" id="admin_image">
                                        <div class="user-thumb user-thumb--edit">
                                            <div class="custom-file">
                                                <input class="custom-file__input" id="profile_photo" name="photo" type="file">
                                                <label class="custom-file__label" for="profile_photo">

                                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                        <img class="rounded-circle" width="150" src="{{ Storage::url(Auth::user()?->profile_photo_path) }}" alt="{{ Auth::user()?->name }}" />
                                                    @else
                                                        <img src="{{ $user->gender == 'male' ? asset('backend/assets/img/profile/male.jpg'):($user->gender == 'female' ? asset('backend/assets/img/profile/female.jpg'):asset('backend/assets/img/profile/other.png'))  }}" class="rounded-circle " width="150">
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <p class="mb-1 font-weight-bold"><label for="name">{{ __('Name') }}:</label> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="name" id="name" value="{{$user->name}}" class="form-control rounded"
                                               aria-label="Large" placeholder="{{__('Name in English')}}" aria-describedby="inputGroup-sizing-sm">
                                        <br>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>


                                    <p class="mb-1 font-weight-bold"><label for="email">{{ __('Email') }}:</label> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="email" name="email" id="email" value="{{$user->email}}" class="form-control rounded"
                                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                                        <br>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold"><label for="phone">{{ __('Phone') }}:</label> </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="tel" name="phone" id="phone" value="{{$user->phone}}" class="form-control rounded"
                                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                                        <br>
                                        @if ($errors->has('phone'))
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold"><label for="address">{{ __('Address') }}:</label> </p>
                                    <div class="form-group mb-3">
                                        <textarea name="address" id="address" class="form-control" rows="5">{!! $user->address !!}</textarea>
                                        <br>
                                        @if ($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="float-right">
                                <button class="btn rounded btn-dashboard-theme" type="submit">{{ __('Save') }}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
