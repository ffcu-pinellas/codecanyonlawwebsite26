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
                <div class="card-body">
                    <form action="{{ route('admin.settings.logo-favicon-save') }}" method="POST" enctype="multipart/form-data" class="wma-form">
                        @csrf
                        <p class="mb-3 font-weight-bold">{{__('Logo')}}: <code>{{__('expected size is 64x64px')}}</code></p>
                        <div class="form-row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="site-logo " id="site-logo">
                                        <div class="input-images"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6  d-md-block  d-sm-none">
                                <div class="img-favicon">
                                    <img src="{{$logoSettings?$logoSettings->logo:'' }}" alt="Og Meta Image" class="img-thumbnail img-fluid">
                                </div>

                            </div>
                        </div>

                        <p class="my-3 font-weight-bold">{{__('Favicon')}}: <code>{{__('expected size is 32x32px')}}</code></p>
                        <div class="form-row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="site-favicon" id="site-favicon">
                                        <div class="input-images"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6  d-md-block  d-sm-none">
                                <div class="img-favicon">
                                    <img src="{{ $logoSettings?$logoSettings->favicon:'' }}" alt="Og Meta Image" class="img-thumbnail img-fluid" width="50%">
                                </div>

                            </div>

                        </div>



                        <div class="wizard-action text-left">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit')}}</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('page-script')
    @include('backend.pages.settings.internal-assets.js.logo-page-scripts')
    @include('backend.layouts.message')
@endsection
