@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')
<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{__('Home')}}</a>
                <span class="breadcrumb-item active">{{__($title)}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header">
                    <h6 class="card-title">{{__($title)}}</h6>
                </div>
                <div class="card-body ">
                    <form action="{{ route('admin.settings.smtp-save') }}" method="POST" enctype="multipart/form-data" class="wma-form">
                        @csrf
                        <p class="mb-1">{{__('Mail Driver')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="mail_driver" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Mail Driver')}}" value="{{ env('MAIL_MAILER') }}">
                            <br>
                            @if ($errors->has('mail_driver'))
                            <span class="text-danger">{{ $errors->first('mail_driver') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Mail Host')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="mail_host" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Mail Host')}}" value="{{ env('MAIL_HOST') }}">
                            <br>
                            @if ($errors->has('mail_host'))
                            <span class="text-danger">{{ $errors->first('mail_host') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Mail Port')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="mail_port" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Mail Port')}}" value="{{ env('MAIL_PORT') }}">
                            <br>
                            @if ($errors->has('mail_port'))
                            <span class="text-danger">{{ $errors->first('mail_port') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Mail User Name')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="mail_username" class="form-control " aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Mail User Name')}}" value="{{ env('MAIL_USERNAME') }}">
                            <br>
                            @if ($errors->has('mail_username'))
                            <span class="text-danger">{{ $errors->first('mail_username') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Mail Password')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="mail_password" class="form-control " aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Mail Password:')}}" value="{{ env('MAIL_PASSWORD') }}">
                            <br>
                            @if ($errors->has('mail_password'))
                            <span class="text-danger">{{ $errors->first('mail_password') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Mail Encryption')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="mail_encryption" class="form-control " aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                placeholder="{{__('Mail Encryption')}}n" value="{{ env('MAIL_ENCRYPTION') }}">
                            <br>
                            @if ($errors->has('mail_encryption'))
                            <span class="text-danger">{{ $errors->first('mail_encryption') }}</span>
                            @endif
                        </div>
                        <div class="wizard-action text-left">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit form')}}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('page-script')
    @include('backend.layouts.message')
@endsection
