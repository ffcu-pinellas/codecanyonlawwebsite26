@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <div class="container">
        <div class="row mt-5">
        <form action="{{ route('client.financial-relief') }}" method="post" enctype="multipart/form-data" class="w-75 mx-auto">
            @csrf
            <p class="mb-1 font-weight-bold"><label for="name">{{ __('Name') }}:</label> </p>
            <div class="input-group input-group-lg mb-3">
                <input type="text" name="name" id="name" value="{{Auth::user()->name}}" class="form-control rounded"
                       aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                <br>
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-row">
                <div class="col-md-6 pl-0">
                    <p class="mb-1 font-weight-bold"><label for="email">{{ __('Email') }}:</label> </p>
                    <div class="input-group input-group-lg mb-3">
                        <input type="tel" name="email" id="email" value="{{Auth::user()->email}}" class="form-control rounded"
                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                        <br>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 pr-0">
                    <p class="mb-1 font-weight-bold"><label for="phone">{{ __('Phone') }}:</label> </p>
                    <div class="input-group input-group-lg mb-3">
                        <input type="tel" name="phone" id="phone" value="{{Auth::user()->phone}}" class="form-control rounded"
                               aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                        <br>
                        @if ($errors->has('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <p class="mb-1 font-weight-bold"><label for="address">{{ __('Mailing Address') }}:</label> </p>
            <div class="input-group input-group-lg mb-3">
                <input type="text" name="address" id="address" value="{{Auth::user()->address}}" class="form-control rounded"
                       aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                <br>
                @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
            </div>

            <p class="mb-1 font-weight-bold"><label for="reason">{{ __('Primary Reason for Requesting Relief') }}:</label> </p>
            <div class="input-group input-group-lg mb-3">
                <input type="text" name="reason" id="reason" value="{{old('reason')}}" class="form-control rounded"
                       aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                <br>
                @if ($errors->has('reason'))
                    <span class="text-danger">{{ $errors->first('reason') }}</span>
                @endif
            </div>

            <p class="mb-1 font-weight-bold"><label for="details">{{ __('Additional Details Related to Your Request (Optional)') }}:</label> </p>
            <div class="form-group mb-3">
                <textarea name="details" id="details" class="form-control rounded" rows="5">{{ old('details') }}</textarea>
                <br>
                @if ($errors->has('details'))
                    <span class="text-danger">{{ $errors->first('details') }}</span>
                @endif
            </div>

            <p class="mb-1 font-weight-bold"><label for="offer">{{ __('Proposed Resolution / Your Offer') }}:</label> </p>
            <div class="form-group mb-3">
                <textarea name="offer" id="offer" class="form-control rounded" rows="5">{{ old('offer') }}</textarea>
                <br>
                @if ($errors->has('offer'))
                    <span class="text-danger">{{ $errors->first('offer') }}</span>
                @endif
            </div>

            <p class="mb-1 font-weight-bold"><label for="file">{{ __('Choose a file') }}:</label> </p>
            <div class="form-group mb-3">
                <input type="file" name="file" id="caseFile" hidden/>
                <label for="caseFile" class="caseFileLabel"><i class="fas fa-cloud-upload-alt"></i></label>
                <br>
                @if ($errors->has('file'))
                    <span class="text-danger">{{ $errors->first('file') }}</span>
                @endif
                <p id="showSelectedFile"></p>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-dashboard-theme w-75">Submit</button>
            </div>

        </form>
    </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/js/file-name-view.js') }}"></script>
@endsection

