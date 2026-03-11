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
                            <div class="table-responsive style-scroll">
                            <table id="slider" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="15%">{{__('Serial')}}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Website') }}</th>
                                    <th>{{ __('Message') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contacts as $value)
                                    <tr>
                                        <th>{{ $loop->index+1 }}</th>
                                        <td>{{ $value->f_name.' '.$value->l_name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td class="text-center">
                                            @if($value->website)
                                                <a href="{{ $value->website }}" target="_blank"><i class="fa fa-globe fa-2x"></i></a>
                                            @endif
                                        </td>
                                        <td>{{ $value->message }}</td>
                                        <td>{{$value->status == 1? "Unread" : "Read"}}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.contact.view',$value->id) }}">
                                                    <button type="button" class="btn btn-sm btn btn-success m-1 blogCategoryEditBtn" data-id="{{ $value->id }}">{{ __('View') }}</button>
                                                </a>
                                                <a href="javascript:void(0)" title="{{__('Delete')}}" class="sliderDestroyBtn">
                                                    <button type="button" class="btn btn-sm btn btn-danger m-1">{{__('Delete')}}</button>
                                                    <form action="{{ route('admin.contact.destroy', $value->id) }}" method="post" class="deleteForm">
                                                        @csrf
                                                        @method('delete')
                                                        <input type="hidden" name="_method" value="delete">
                                                    </form>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.contacts.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
@endsection
