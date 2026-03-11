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
                            <span class="card-title font-weight-bold">{{ __('Services Page Settings') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="{{ route('admin.page-settings.contact-page.store-img') }}" method="post" class="d-none" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page" value="services">
                        <input type="hidden" name="group" value="services_breadcumb_bg_img">
                    </form>
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{__('services page SEO Settings')}}</h6>
                    </div>
                    <div class="card-body ">
                        <form action="{{ route('admin.page-settings.page.seo') }}" method="POST" enctype="multipart/form-data" class="wma-form">
                            @csrf
                            <input type="hidden" name="page" value="services">
                            <p class="mb-1">{{__('Meta Keyword')}}: <code>{{__('Put comma(,) for separate the meta key')}}</code></p>
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" name="meta_keyword" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                       placeholder="{{__('Meta Keyword')}}" data-role="tagsinput" value="{{$settingsPage?$settingsPage->meta_keyword:''}}">
                                <br>
                                @if ($errors->has('meta_keyword'))
                                    <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                                @endif
                            </div>

                            <p class="mb-1">{{__("Meta Description")}}: <code>{{__('maximum 50 word')}}</code></p>
                            <div class="input-group mb-3">
                            <textarea class="form-control" name="meta_description" aria-label="With textarea"
                                      rows="4">{{ $settingsPage?$settingsPage->meta_description:''}}</textarea>
                                <br>
                                @if ($errors->has('meta_description'))
                                    <span class="text-danger">{{ $errors->first('meta_description') }}</span>
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
    <script src="{{ asset('backend/page-section-settings/script.js') }}"></script>
    @include('backend.layouts.message')
@endsection
