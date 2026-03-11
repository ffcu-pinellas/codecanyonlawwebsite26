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
                    <a class="breadcrumb-item text-white" href="{{ route('admin.partner.index') }}">{{__('Partner')}}</a>
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
                    <form action="{{ $partner? route('admin.partner.update',$partner->id) : route('admin.partner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($partner)
                            @method('PATCH')
                        @endif
                        <div class="card-body ">
                           <div class="form-row">
                               <div class="col-md-8">
                                   <p class="mb-1 font-weight-bold">{{__('Name :')}} </p>
                                   <div class="input-group input-group-lg mb-3">
                                       <input type="text" name="name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                              placeholder="{{__('Slider title')}}" value="{{ $partner?$partner->name:old('name') }}">
                                       <br>
                                       @if ($errors->has('name'))
                                           <span class="text-danger">{{ $errors->first('name') }}</span>
                                       @endif
                                   </div>
                                   <p class="mb-1 font-weight-bold">{{__('URL :')}} </p>
                                   <div class="input-group input-group-lg mb-3">
                                       <input type="text" name="url" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                              placeholder="{{__('Slider title')}}" value="{{ $partner?$partner->url:old('name') }}">
                                       <br>
                                       @if ($errors->has('name'))
                                           <span class="text-danger">{{ $errors->first('name') }}</span>
                                       @endif
                                   </div>
                                   <table class="table table-responsive-sm">
                                       <label for="programStatus">
                                           <span class="font-weight-bold">{{__('Publish Status')}}</span>
                                       </label>
                                       <tbody>
                                       <tr>
                                           <td>
                                               <label class="switch float-left">
                                                   <input type="checkbox" name="status" {{ $partner?($partner->status==true?'checked':''):'' }} id="programStatus">
                                                   <span class="slider round"></span>
                                               </label>
                                           </td>
                                       </tr>
                                       </tbody>
                                   </table>
                               </div>

                               <div class="col-md-4">
                                   <div class="pl-3 pr-2">
                                       <p class="mb-2 font-weight-bold">{{__('Image :')}} <code>{{__('Acceptable image size 160 x 60
                                           pixel')}}</code></p>
                                       <div class="mb-3">
                                           <div class="partner_bg_image" id="partner_bg_image">
                                               <div class="input-images"></div>
                                           </div>
                                       </div>
                                   </div>

                                   @if($partner)
                                   <div class="pl-3 pr-2 ">
                                       <p class="mb-2 font-weight-bold ">{{__('Old Image :')}} </p>
                                       <img src="{{ asset('upload/partners/'.$partner->image) }}" alt="" width="100" height="150" class="img-thumbnail bg-secondary">
                                   </div>
                                   @endif

                               </div>
                           </div>


                        </div>
                        <div class="card-footer">
                            <div class="wizard-action text-left">
                                <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit form')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.partners.internal-assets.js.partner-page-scripts')
    @include('backend.layouts.message')
@endsection
