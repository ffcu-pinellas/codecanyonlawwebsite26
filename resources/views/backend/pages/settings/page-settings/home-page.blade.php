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

        <!-- Slider -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Slider') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="slider">
                    </form>
                </div>
            </div>
        </div>

        <!-- About -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('About') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="{{ route('admin.page-settings.contact-page.store-img') }}" method="post" class="d-none" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="about">
                    </form>
                </div>
            </div>
        </div>

        <!-- Service -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Service') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="{{ route('admin.page-settings.contact-page.store-img') }}" method="post" class="d-none" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="service">
                    </form>
                </div>
            </div>
        </div>

        <!-- Testimonial -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Testimonial') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="testimonial">
                    </form>
                </div>
            </div>
        </div>

        <!-- Appointment -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Appointment') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="appointment">
                    </form>
                </div>
            </div>
        </div>

        <!-- case study -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('case study') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="case_study">
                    </form>
                </div>
            </div>
        </div>

        <!-- Attorney -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Attorney') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="attorney">
                    </form>
                </div>
            </div>
        </div>

        <!-- Blog -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Blog') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="blog">
                    </form>
                </div>
            </div>
        </div>

        <!-- Partner -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Partner') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="" method="post" class="d-none">
                        <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="partner">
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
