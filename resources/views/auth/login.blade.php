@extends('auth.layouts.master-layout')

@section('content')
    <div class="card card-light p-0 my-sm-4 my-0 bg-dark">
        <div class="box-account">
            <img class="box-account-logo" src="{{ asset($logoFavicon?$logoFavicon->logo:'') }}"/>
            <h6 class="box-account-title text-white"> {{__('Login')}} </h6>
            <form class="box-account-form" action="{{ route('login') }}" method="post">
                @csrf
                <span class="reauth-email"> </span>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Email" id="inputPseudo">
                </div>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <div class="form-gropu">
                    <input class="form-control" type="password" name="password" required placeholder="Password" id="inputPassword">
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label text-white" for="customCheck1">{{__('Remember me')}}</label>
                </div>
                <button class="btn btn-primary btn-lg btn-block btn-wave-light" type="submit">{{__('Login')}}</button>
                @if(env('APP_DEMO', false))
                    <div class="table-responsive">
                        <table class="CaptionCont fs-16 mt-4 table table-bordered table-dark table-responsive">
                            <tbody>
                            <tr>
                                <td>admin@demo.com</td>
                                <td>admin123</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-danger p-1 login-credential-copy-action"
                                            data-user="admin">{{__("Copy")}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>attorney@demo.com</td>
                                <td>attorney123</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-danger p-1 login-credential-copy-action"
                                            data-user="attorney">{{__("Copy")}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>user@demo.com</td>
                                <td>user1234</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-danger p-1 login-credential-copy-action"
                                            data-user="user">{{__("Copy")}}</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                @endif
            </form>
            @if (Route::has('password.request'))
                <p class="box-account-text text-white">{{__('Forgot your password')}} ? <a href="{{ route('password.request') }}"
                                                                                 class="text-info text-grey">{{__('Recover')}}</a></p>
            @endif
            <p class="box-account-text text-white">{{__('Create a free account')}} ? <a href="{{ route('register') }}"
                                                                                       class="text-info text-grey">{{__('Register Now')}}</a></p>
        </div>
    </div>
@endsection
