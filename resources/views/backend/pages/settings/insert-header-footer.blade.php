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
                    <form action="{{ route('admin.settings.insert-header-footer-save') }}" method="POST" enctype="multipart/form-data" class="wma-form">
                        @csrf
                        <p class="mb-1">{{__('Header')}}:</p>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="header" aria-label="With textarea"
                                rows="15">{{ $headerFooterData?$headerFooterData->header:'' }}</textarea>
                            <br>
                            @if ($errors->has('header'))
                            <span class="text-danger">{{ $errors->first('header') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Footer')}}:</p>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="footer" aria-label="With textarea"
                                rows="15">{{ $headerFooterData?$headerFooterData->footer:'' }}</textarea>
                            <br>
                            @if ($errors->has('footer'))
                            <span class="text-danger">{{ $errors->first('footer') }}</span>
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
