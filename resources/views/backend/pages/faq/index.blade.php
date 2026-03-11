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
                            <a href="{{ route('admin.faq.create') }}" id="addBlogCategoryBtn" class="btn btn-danger btn-sm rounded"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                    </div>
                    <div class="card-body ">
                        <table class="table bapric_table">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Serial')}}</th>
                                <th scope="col">{{ __('Question') }}</th>
                                <th scope="col">{{ __('Answer') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                           @foreach($faq as $value)
                                    <tr>
                                        <th>{{ $loop->index+1 }}</th>
                                        <td>{{ $value->question }}</td>
                                        <td>{{ Str::limit($value->answer,100,'...') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.faq.edit',$value->id) }}">
                                                    <button type="button" class="btn btn-sm btn btn-success m-1 blogCategoryEditBtn" data-id="{{ $value->id }}">{{ __('Edit') }}</button>
                                                </a>
                                                <a href="javascript:void(0)" title="{{__('Delete')}}" class="sliderDestroyBtn">
                                                    <button type="button" class="btn btn-sm btn btn-danger m-1">{{__('Delete')}}</button>
                                                    <form action="{{ route('admin.faq.destroy', $value->id) }}" method="post" class="deleteForm">
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
@endsection

@section('page-script')
    @include('backend.pages.faq.internal-assets.js.delete-warning')
@endsection
