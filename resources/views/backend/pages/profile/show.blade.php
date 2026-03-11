@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')
    <!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
    <div id="wrapper-content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                        <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                        <span class="breadcrumb-item active">{{ __($title) }}</span>
                        <span class="breadcrumb-info" id="time"></span>
                    </nav>
                </div>
            </div>

            <div class="row">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="col-12">
                        @include('backend.pages.profile.update-profile-information-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="col-12">
                        @include('backend.pages.profile.update-password-form')
                    </div>
                @endif

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="col-12">
                        @include('backend.pages.profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
@endsection

@section('page-script')
    @include('backend.pages.profile.internal-assets.js.profile-page-scripts')
    <script src="{{ asset('backend/assets/js/form-summerNote.js') }}"></script>
    @include('backend.layouts.message')
@endsection
