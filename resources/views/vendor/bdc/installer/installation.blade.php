@extends('views::layout')

@section('title', 'Home')
@section('preloader')
    <div id="preloader" class="d-none">
        <div id="status">
            <img src="{{ asset('frontend/assets/img/loader.svg') }}" alt="img"/>
            <span class="loading-text">Your install is processing. Please wait a few minutes.</span>
        </div>

    </div>
@endsection
@section('content')
    <div class="p-sm-25 p-15 bd-one bd-t-zero bd-c-stroke bd-ra-8 bd-tl-ra-0 bd-tr-ra-0 bg-white mb-25">
        <h4 class="bg-body-bg flex-row-reverse fs-6 my-1 p-10 rounded-2 section-title text-black" style="background-color: #c6e4e9 !important;">
            Check Your Database Configuration </h4>
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$errors->first()}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form action="{{ route('BdcInstaller::process.store') }}" method="POST">
            @csrf
            <div class="single-section">
                <div class="row rg-16 py-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="AppName">App Name</label>
                            <input type="text" class="form-control mt-10 p-10" id="AppName" name="app_name"
                                   value="{{ $_ENV['APP_NAME'] }}" placeholder="ZaiInstaller"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="AppURL">App URL</label>
                            <input type="text" class="form-control mt-10 p-10" id="AppURL" name="app_url"
                                   value="{{ $_ENV['APP_URL'] }}" placeholder="http://localhost:8000"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DatabaseHost">Database Host</label>
                            <input type="text" class="form-control mt-10 p-10" id="DatabaseHost" name="db_host"
                                   value="{{ $_ENV['DB_HOST'] }}" placeholder="localhost"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DatabaseUser">Database User</label>
                            <input type="text" class="form-control mt-10 p-10" id="DatabaseUser" name="db_user"
                                   value="{{ $_ENV['DB_USERNAME'] }}" placeholder="root"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DatabaseName">Database Name</label>
                            <input type="text" class="form-control mt-10 p-10" id="DatabaseName" name="db_name"
                                   value="{{ $_ENV['DB_DATABASE'] }}" placeholder="Type your database name"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Password">Password</label>
                            <input type="password" class="form-control mt-10 p-10" id="Password" name="db_password"
                                   value="{{ $_ENV['DB_PASSWORD'] }}" placeholder="password"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MailHost">Mail Host</label>
                            <input type="text" class="form-control mt-10 p-10" id="MailHost" name="mail_host"
                                   value="{{ $_ENV['MAIL_HOST'] }}" placeholder="Type Your mail host name"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MailPort">Port</label>
                            <input type="text" class="form-control mt-10 p-10" id="MailPort" name="mail_port"
                                   value="{{ $_ENV['MAIL_PORT'] }}" placeholder="Type your mail port"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MailUsername">Username</label>
                            <input type="text" class="form-control mt-10 p-10" id="MailUsername" name="mail_username"
                                   value="{{ $_ENV['MAIL_USERNAME'] }}" placeholder="Type your mail username"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MailPassword">Password</label>
                            <input type="password" class="form-control mt-10 p-10" id="MailPassword"
                                   name="mail_password" value="{{ $_ENV['MAIL_PASSWORD'] }}" placeholder="password"/>
                        </div>
                    </div>
                </div>
            </div>
            <button id="submitNext" type="submit"
                    class="bg-para-text w-100 py-10 rounded-3 text-bg-success fw-1000 border-0" style="background-color: #24bbd3!important;">Finished
            </button>
        </form>
    </div>
@endsection
