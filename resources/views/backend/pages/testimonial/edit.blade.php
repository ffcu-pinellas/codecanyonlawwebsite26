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
                           href="{{route('admin.testimonial.index')}}">{{__('Home')}}</a>
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
                        <form class="" action="{{route('admin.testimonial.update',$values->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-7">
                                        <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Name:')}}</label> </p>
                                        <div class="input-group input-group-lg mb-3">
                                            <input type="text" name="name" id="name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                                   placeholder="{{__('Name')}}" value="{{$values->name}}">
                                            <br>
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                        <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Designation:')}}</label> </p>
                                        <div class="input-group input-group-lg mb-3">
                                            <input type="text" name="designation" id="designation" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                                   placeholder="{{__('Designation')}}" value="{{$values->designation}}">
                                            <br>
                                            @if ($errors->has('designation'))
                                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="about" class="card-title font-weight-bold">{{__('Testimonial')}}:</label>
                                            <textarea rows="10" name="testimonial" id="testimonial" class="form-control rounded" required>{{$values->testimonial}}</textarea>
                                        </div>

                                    </div>
                                    <div class="col-md-5">
                                        <p class="h6 mb-3">{{ __('Image')}}:<code>{{__('Acceptable image size 96 x 96 pixel')}}</code></p>
                                        <div class="">
                                            <div class="subject-thumbnail" id="subject-thumbnail">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <img src="{{asset('/upload/testimonial/'.$values->image)}}" alt="Icon" width="100" height="100">
                                        </div><br>
                                    </div>
                                    <table class="table table-responsive-sm">
                                        <label for="programStatus">
                                            <span class="font-weight-bold">{{__('Publish Status:')}}</span>
                                        </label>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label class="switch float-left">
                                                    <input type="checkbox" name="status" {{ $values?($values->status==true?'checked':''):'' }} id="programStatus">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit Form')}}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.testimonial.internal-assets.js.testimonial-scripts')
    @include('backend.layouts.message')
@endsection
