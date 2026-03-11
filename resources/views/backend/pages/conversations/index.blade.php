@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Bapric').' | '.$title)

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
                        <span class="breadcrumb-item active">{{__($title)}}</span>
                        <span class="breadcrumb-info" id="time"></span>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-dark bg-dark">
                        <div class="card-header d-block">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <h6 class="card-title">{{__($title)}}</h6>
                                </div>
                                <div class="col-md-6 col-sm-12 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <table id="slider" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Serial')}}</th>
                                    <th scope="col">{{ __('Avatar') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Message') }}</th>
                                    <th scope="col">{{ __('Time') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                {!! $conversations !!}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.appointments.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
@endsection
