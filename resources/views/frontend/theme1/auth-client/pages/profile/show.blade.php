@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')
    <!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
    <div class="row mt-5">
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            <div class="col-12">
                @include('frontend.theme1.auth-client.pages.profile.update-profile-information-form')
            </div>
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="col-12">
                @include('frontend.theme1.auth-client.pages.profile.update-password-form')
            </div>
        @endif
    </div>
    <!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
@endsection

@section('page-script')

@endsection
