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
                    <a class="breadcrumb-item text-white"
                       href="{{ route('admin.contact.index') }}">{{__('All Contacts')}}</a>
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
                    <form action="" method="POST">

                        <div class="card-body ">
                            <div class="form-row">

                                <p class="mb-1 font-weight-bold">{{__('Name :')}} </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" name="title" class="form-control" aria-label="Large"
                                           aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Service title')}}"
                                           value="{{ $contact->f_name.' '.$contact->l_name  }}" readonly>
                                </div>

                                <p class="mb-1 font-weight-bold">{{__('Email :')}} </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" name="title" class="form-control" aria-label="Large"
                                           aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Email')}}" value="{{ $contact->email  }}" readonly>
                                </div>

                                <p class="mb-1 font-weight-bold">{{__('Website :')}} </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" name="title" class="form-control" aria-label="Large"
                                           aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Website URL')}}" value="{{ $contact->website }}" readonly>
                                </div>


                                <p class="mb-1 font-weight-bold">{{__('Description :')}} </p>
                                <div class="input-group mb-3">
                                    <textarea class="form-control" name="description" aria-label="With textarea"
                                              rows="5" placeholder="{{__('Description')}}"
                                              readonly>{!! clean($contact?$contact->message:'')!!}</textarea>
                                </div>

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
@endsection
