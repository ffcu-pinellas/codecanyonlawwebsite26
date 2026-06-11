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
                    <a class="breadcrumb-item text-white" href="{{ route('admin.financial-relief.index') }}">{{__('Financial Relief Requests')}}</a>
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
                    <form action="javascript:void(0)" method="POST" >

                        <div class="card-body ">
                            <div class="form-row">

                                    <p class="mb-1 font-weight-bold">{{__('User Name :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="" value="{{ $hardship->user->name  }}" readonly>
                                    </div>
                                <p class="mb-1 font-weight-bold">{{__('Name :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="" value="{{ $hardship->name  }}" readonly>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Email :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="" value="{{ $hardship->email  }}" readonly>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Phone :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="" value="{{ $hardship->phone  }}" readonly>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Address :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="" value="{{ $hardship->address  }}" readonly>
                                    </div>

                                    <p class="mb-1 font-weight-bold">{{__('Reason :')}} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                               placeholder="" value="{{ $hardship->reason  }}" readonly>
                                    </div>

                                @if($hardship->details)
                                    <p class="mb-1 font-weight-bold">{{__('details :')}} </p>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="details" aria-label="With textarea" rows="5" placeholder="Write description here..." readonly>{!! clean($hardship->details)!!}</textarea>
                                    </div>
                                @endif

                                <p class="mb-1 font-weight-bold">{{__('Proposed Resolution :')}} </p>
                                <div class="input-group mb-3">
                                    <textarea class="form-control" name="offer" aria-label="With textarea" rows="5" placeholder="Write description here..." readonly>{!! clean($hardship->offer)!!}</textarea>
                                </div>

                        </div>


                        </div>

                        <div class="card-footer d-flex justify-content-between bg-dark border-top border-secondary">
                            <div>
                                @if($hardship->file)
                                    <a href="{{ asset($hardship->file) }}" class="btn btn-info mr-2" target="_blank">
                                        <i class="fas fa-file-download mr-1"></i> {{ __('Download Document') }} ({{ $hardship->file_name }})
                                    </a>
                                @endif
                            </div>
                            <div class="d-flex">
                                <form action="{{ route('admin.financial-relief.approve-case', $hardship->id) }}" method="POST" class="mr-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check-circle mr-1"></i> {{ __('Approve & Initialize Case Representation') }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.financial-relief.destroy', $hardship->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt mr-1"></i> {{ __('Delete Request') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.services.internal-assets.js.service-page-scripts')
    @include('backend.layouts.message')
@endsection
