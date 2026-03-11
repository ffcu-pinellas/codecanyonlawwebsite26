@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
    @include('backend.pages.settings.page-settings.internal-assets.css.page-settings-css')
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

        <!-- breadcamb bg image -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Client Dashboard Settings') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="{{ route('admin.page-settings.contact-page.store-img') }}" method="post" class="d-none" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page" value="client_dashboard">
                        <input type="hidden" name="group" value="client_dashboard_breadcumb_bg_img">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/page-section-settings/script.js') }}"></script>
    @include('backend.layouts.message')
@endsection
