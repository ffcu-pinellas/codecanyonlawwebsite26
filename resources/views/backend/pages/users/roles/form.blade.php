@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
    <link href="{{asset('backend/assets/plugin/select2/select2.min.css')}}" rel="stylesheet"/>
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white"
                       href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                    <a class="breadcrumb-item text-white" href="{{ route('admin.user.role.index') }}">{{__('All Roles')}}</a>
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
                    <form class="" action="{{ $role? route('admin.user.role.update',$role->id) : route('admin.user.role.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($role)
                            @method('put')
                        @endif
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-7">
                                    <p class="mb-1 font-weight-bold">{{__('Name :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="{{__('Name')}}" value="{{ $role?clean($role->name):old('name') }}" {{ $role?($role->name == 'attorney'? 'readonly':($role->name == 'client'? 'readonly':'')):'' }}>
                                        <br>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Permission :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <select name="permission[]" id="permission" multiple class="form-control permission_multiple_input">
                                            <option value="{{ null }}">{{ __('Select One') }}</option>
                                            @foreach($permissions as $permission)
                                                <option value="{{ $permission->id }}">{{ clean($permission->name) }}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        @if ($errors->has('permission'))
                                            <span class="text-danger">{{ $errors->first('permission') }}</span>
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
    <script src="{{ asset('backend/assets/plugin/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/user-role-and permission.js') }}"></script>
    @include('backend.layouts.message')
@endsection
