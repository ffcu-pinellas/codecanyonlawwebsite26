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
                    <a class="breadcrumb-item text-white" href="{{route('admin.testimonial.index')}}">{{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{__(ucwords($title))}}</span>
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
                                <a href="{{route('admin.testimonial.form')}}" class="btn btn-danger btn-sm rounded"> <i class="material-icons">add</i></a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body ">
                        <div class="table-responsive style-scroll">

                            <table id="bdcoder" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{__('SL No.')}}</th>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Designation')}}</th>
                                    <th>{{__('Testimonial')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                            @foreach($values as $data)
                                    <tr>
                                        <th>{{__($loop->index+1)}}</th>
                                        <th>
                                            <img src="{{asset('/upload/testimonial/'.$data->image)}}" height="70" width="70" alt="">
                                        </th>
                                        <th>{{$data->name}}</th>
                                        <th>{{$data->designation}}</th>
                                        <th>{{$data->testimonial}}</th>
                                        <th>
                                            <label class="switch">
                                                <input type="checkbox" {{ $data->status?'checked':'' }} id="{{ $data->id }}" class="teacherActivationBtn">
                                                <span class="slider round"></span>
                                            </label>
                                        </th>
                                        <td class="text-center">
                                            <a href="{{ route('admin.testimonial.edit',$data->id) }}">
                                                <button type="button" class="btn btn-sm btn btn-success m-1 blogCategoryEditBtn" data-id="{{ $data->id }}">{{ __('Edit') }}</button>
                                            </a>
                                            <a href="javascript:void(0)" title="{{__('Delete')}}" class="sliderDestroyBtn">
                                                <button type="button" class="btn btn-sm btn btn-danger m-1">{{__('Delete')}}</button>
                                                <form action="{{ route('admin.testimonial.delete',$data->id) }}" method="post" class="deleteForm">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" name="_method" value="delete">
                                                </form>
                                            </a>

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
    @include('backend.pages.testimonial.internal-assets.js.delete-warning')
@endsection
