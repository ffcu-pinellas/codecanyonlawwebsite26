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
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h6 class="card-title">{{__($title)}}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="table-responsive style-scroll">
                        <table id="bdcoder" class="table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="10%">{{__('SL No')}}.</th>
                                <th>{{__('Name')}} </th>
                                <th class="text-center">{{__('Option')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menus as $data)

                                <tr>
                                    <th width="10%">{{$loop->index+1}}</th>
                                    <th width="60%" class="text-capitalize">{{__($data->name)}}</th>
                                    <td width="30%" class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-success  btn-circle view-list-menu-btn">
                                            <i class="material-icons">view_list</i>
                                            <form action="{{ route('admin.menu.item.index') }}" method="get">
                                                @csrf
                                                @method('get')
                                                <input type="hidden" name="menu" value="{{ $data->id }}">
                                            </form>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-info  btn-circle edit-menu-btn" data-row="{{ $data->id }}">
                                            <i class="material-icons">edit</i>
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
<!-- Model for dynamic menu -->
<div class="modal fade" id="menuCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Menu Group')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.menu.category.store') }}" method="post">
                <div class="modal-body">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label for="menuCategoryName" class="card-title font-weight-bold"><h6>{{__('Name')}}</h6></label>
                        <input type="text" name="name" id="menuCategoryName" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-danger rounded">{{__('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
    <script src="{{asset('backend/assets/js/tables-datatable.js')}}"></script>
    @include('backend.pages.settings.menu-settings.internal-assets.js.menu-js')
@endsection
