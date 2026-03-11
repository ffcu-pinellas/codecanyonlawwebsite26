@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')

<div id="wrapper-content">
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                <span class="breadcrumb-item active">{{ __($title) }}</span>
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
                            <h6 class="card-title lh-35">{{ __($title) }}</h6>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                                <a href="{{ route('admin.user.role.create') }}" id="addBlogCategoryBtn" class="btn btn-danger btn-sm rounded"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive style-scroll">
                        <table id="slider" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="5%">{{__('SL')}}</th>
                                <th>{{__('Name')}}</th>
                                <th width="10%">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ strtoupper($role->name) }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @if($role->name == 'admin' || $role->name == 'attorney')
                                                <a href="{{ route('admin.user.role.edit',$role->id) }}">
                                                    <button type="button" class="btn btn-sm btn btn-success m-1 roleEditBtn" data-id="{{ $role->id }}">{{ __('Edit') }}</button>
                                                </a>
                                                <a href="javascript:void(0)" title="{{__('Not Deletable')}}" class="roleDestroyBtn">
                                                    <button type="button" class="btn btn-sm btn btn-danger m-1 disabled">{{__('Delete')}}</button>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.user.role.edit',$role->id) }}">
                                                    <button type="button" class="btn btn-sm btn btn-success m-1 roleEditBtn" data-id="{{ $role->id }}">{{ __('Edit') }}</button>
                                                </a>
                                                <a href="javascript:void(0)" title="{{__('Delete')}}" class="roleDestroyBtn">
                                                    <button type="button" class="btn btn-sm btn btn-danger m-1">{{__('Delete')}}</button>
                                                    <form action="{{ route('admin.user.role.destroy', $role->id) }}" method="post" class="deleteForm">
                                                        @csrf
                                                        @method('delete')
                                                        <input type="hidden" name="_method" value="delete">
                                                    </form>
                                                </a>
                                            @endif
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
    <script src="{{ asset('backend/assets/js/user-role-and permission.js') }}"></script>
@endsection
