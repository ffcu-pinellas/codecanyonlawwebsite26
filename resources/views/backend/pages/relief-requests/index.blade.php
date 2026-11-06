@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert').' | '.$title)

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
                            <table id="slider" class="table table-striped table-bordered table-dark miw-500" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Serial')}}</th>
                                    <th scope="col">{{__('User')}}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col">{{ __('File') }}</th>
                                    <th scope="col">{{ __('Reason') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hardships as $value)
                                    <tr>
                                        <th>{{ $loop->index+1 }}</th>
                                        <td>{{ clean($value->user->name ?? 'Guest Client') }}</td>
                                        <td>{{ clean($value->name) }}</td>
                                        <td>{{ clean($value->email) }}</td>
                                        <td>{{ clean($value->phone) }}</td>
                                        <td>
                                            @if($value->file)
                                                <a href="{{ asset($value->file) }}" target="_blank" class="badge badge-info"><i class="fas fa-paperclip mr-1"></i>{{ clean($value->file_name ?: 'Document') }}</a>
                                            @else
                                                <span class="text-muted small">None</span>
                                            @endif
                                        </td>
                                        <td>{{ substr(clean($value->reason),0,60).'....' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <form action="{{ route('admin.financial-relief.approve-case', $value->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Promote this lead/intake to an active legal/CPA case representation?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary m-1" title="{{ __('Promote to Active Case') }}">
                                                        <i class="fas fa-briefcase mr-1"></i>{{ __('Promote') }}
                                                    </button>
                                                </form>

                                                <a href="{{ route('admin.financial-relief.view',$value->id) }}">
                                                    <button type="button" class="btn btn-sm btn-success m-1 blogCategoryEditBtn" data-id="{{ $value->id }}">{{ __('View') }}</button>
                                                </a>

                                                <a href="javascript:void(0)" title="{{__('Delete')}}" class="assistanceDestroyBtn">
                                                    <button type="button" class="btn btn-sm btn-danger m-1">{{__('Delete')}}</button>

                                                    <form action="{{ route('admin.financial-relief.destroy', $value->id) }}" method="post" class="deleteForm">
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
    @include('backend.pages.relief-requests.internal-assets.js.delete-warning')
    @include('backend.layouts.message')
@endsection
