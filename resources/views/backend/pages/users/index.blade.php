@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel') . ' | ' . $title)

@section('page-css')

@endsection

@section('content')
    <div id="wrapper-content">
        <div class="row">
            <div class="col">
                <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                    <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
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
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive style-scroll">
                            <table class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">{{ __('SL') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Address') }}</th>
                                        <th width="10%">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->roles->pluck('name')[0] }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#usermodal"
                                                        class="btn btn-sm btn btn-success m-1 user_btn"
                                                        data-id="{{ $user->id }}">{{ __('view') }}</button>
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
 <!-- Modal -->
 <div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="usermodalLabel"
 aria-hidden="true">
 <div class="modal-dialog  modal-dialog-centered" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="usermodalLabel">{{ __('User') }}</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <form method="patch" action="{{ route('admin.user.save') }}">
             @csrf
             <div class="modal-body" id="user_html">

             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                 <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
             </div>
         </form>
     </div>
 </div>
</div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/js/user-role-and permission.js') }}"></script>
    @include('backend.pages.users.scripts.user-script')
@endsection
