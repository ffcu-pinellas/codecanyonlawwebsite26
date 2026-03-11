@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
    @include('backend.pages.settings.page-settings.internal-assets.css.top-header-settings-css')
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

        <!-- Contact Info -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Top Header') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="{{route('admin.settings.topHeader.store')}}" method="post" class="">
                        @csrf
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="top_header">
                        <div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">{{ __('Show') }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show"  {{$headerSettingValue?($headerSettingValue->show?'checked':''):''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row d-none">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">{{ __('Left Content') }}</label>
                                        <input type="text" name="left_content" id="contact-title" class="form-control" required value="{{$headerSettingValue?$headerSettingValue->left_content:''}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact-line-one" class="card-title">{{ __('Right Content') }}</label>
                                        <input type="text" name="right_content" id="contact-line-one" class="form-control" required value="{{$headerSettingValue?$headerSettingValue->right_content:''}}">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-none">
                            <button type="submit" class="btn btn-danger btn-lg rounded">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/page-section-settings/header-script.js') }}"></script>
    @include('backend.layouts.message')
@endsection
