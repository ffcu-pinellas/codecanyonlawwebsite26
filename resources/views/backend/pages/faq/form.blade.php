@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white"
                       href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.faq.index') }}">{{__('Faq')}}</a>
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
                    <form class="" action="{{ $faq? route('admin.faq.update',$faq->id) : route('admin.faq.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($faq)
                            @method('PATCH')
                        @endif
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 font-weight-bold">{{__('Question:')}} </p>
                                        <textarea rows="10" name="question" id="question" class="form-control rounded" required>{{ $faq?$faq->question:old('question') }}</textarea>
                                        <br>
                                        @if ($errors->has('question'))
                                            <span class="text-danger">{{ $errors->first('question') }}</span>
                                        @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 font-weight-bold">{{__('Answer:')}} </p>
                                        <textarea rows="10" name="answer" id="answer" class="form-control rounded" required>{{ $faq?$faq->answer:old('answer') }}</textarea>
                                        <br>
                                        @if ($errors->has('answer'))
                                            <span class="text-danger">{{ $errors->first('answer') }}</span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
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
