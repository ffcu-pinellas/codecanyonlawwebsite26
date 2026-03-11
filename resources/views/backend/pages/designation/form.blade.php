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
                    <a class="breadcrumb-item text-white" href="{{ route('admin.designation.index') }}">{{__('Designation')}}</a>
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
                    <form class="" action="{{ $designation? route('admin.designation.update',$designation->id) : route('admin.designation.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($designation)
                            @method('PATCH')
                        @endif
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-7">
                                    <p class="mb-1 font-weight-bold">{{__('Name :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Name')}}" value="{{ $designation?$designation->name:old('name') }}">
                                        <br>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
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
