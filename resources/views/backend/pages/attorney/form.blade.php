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
                    @if (empty($page))
                        <a class="breadcrumb-item text-white"
                            href="{{ route('admin.attorney.index') }}">{{ __('Attorney') }}</a>
                    @endif
                    <span class="breadcrumb-item active">{{ __($title) }}</span>
                    <span class="breadcrumb-info" id="time"></span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-dark bg-dark">
                    <div class="card-header">
                        <h6 class="card-title">{{ __($title) }}</h6>
                    </div>
                    <form
                        action="{{ $attorney ? route('admin.attorney.update', $attorney->id) : route('admin.attorney.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($attorney)
                            @method('PATCH')
                        @endif
                        <div class="card-body ">
                            <div class="form-row">
                                <div class="col-md-6 pr-2">
                                    <p class="mb-1 font-weight-bold">{{ __('Name :') }} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="name" class="form-control" aria-label="Large"
                                            aria-describedby="inputGroup-sizing-sm"
                                            placeholder="{{ __('Attorney name') }}"
                                            value="{{ $attorney ? $attorney->name : old('name') }}">
                                        <br>
                                        @if ($errors->has('name'))
                                            <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 pr-2">
                                    <p class="mb-1 font-weight-bold">{{ __('Phone : ') }}</p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror" aria-label="Large"
                                            aria-describedby="inputGroup-sizing-sm"
                                            placeholder="{{ __('Attorney phone') }}"
                                            value="{{ $attorney ? $attorney->phone : old('phone') }}">
                                        <br>
                                        @if ($errors->has('phone'))
                                            <p class="text-danger">{{ $errors->first('phone') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 pr-2">
                                    <p class="mb-1 font-weight-bold">{{ __('Email :') }} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" aria-label="Large"
                                            aria-describedby="inputGroup-sizing-sm"
                                            placeholder="{{ __('Attorney email') }}"
                                            value="{{ $attorney ? $attorney->email : old('email') }}">
                                        <br>
                                        @if ($errors->has('email'))
                                            <p class="text-danger">{{ $errors->first('email') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 pr-2">
                                    <p class="mb-1 font-weight-bold">{{ __('Fax :') }} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="text" name="fax"
                                            class="form-control @error('fax') is-invalid @enderror" aria-label="Large"
                                            aria-describedby="inputGroup-sizing-sm"
                                            placeholder="{{ __('Attorney fax') }}"
                                            value="{{ $attorney ? $attorney->fax : old('fax') }}">
                                        <br>
                                        @if ($errors->has('fax'))
                                            <p class="text-danger">{{ $errors->first('fax') }}</p>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-4 pr-2">
                                    <p class="mb-1 font-weight-bold">{{ __('Designation Name:') }} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <select class="form-control form-control-lg" name="designation_id">
                                            @foreach ($designation as $data)
                                                <option value="{{ $data ? $data->id : old('name') }}"
                                                    @if (!empty($attorney)){{ $attorney->designation->id == $data->id ? 'selected' : '' }}@endif>
                                                    {{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        @if ($errors->has('name'))
                                            <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-2 pl-3">
                                    <p class="mb-1 font-weight-bold">{{ __('Publish Status:') }} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <label class="switch float-left">
                                            <input type="checkbox" name="status"
                                                {{ $attorney ? ($attorney->status == true ? 'checked' : '') : '' }}
                                                id="programStatus">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 font-weight-bold">{{ __('Password:') }}
                                        @if (isset($attorney))
                                            <small
                                                class="small">{{ __('(Enter if you want to change)') }}</small>
                                    </p>
                                    @endif
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" class="form-control" name="password" value=""
                                            placeholder="{{ __('Password') }}">
                                    </div>
                                    @if ($errors->has('password'))
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-3 pl-3">
                                    <p class="mb-1 font-weight-bold">{{ __('Confirm Password:') }} </p>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="password" class="form-control" name="password_confirmation" value=""
                                            placeholder="{{ __('Password') }}">
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">

                                </div>
                            </div>

                            <p class="mb-1 font-weight-bold">{{ __('Address :') }} </p>
                            <div class=" mb-3">
                                <textarea name="address" id="address" rows="6" class="form-control"
                                    placeholder="{{ __('Attorney address here') }}">{!! clean($attorney ? $attorney->address : old('address')) !!}</textarea>
                                <br>
                                @if ($errors->has('address'))
                                    <p class="text-danger">{{ $errors->first('address') }}</p>
                                @endif
                            </div>


                            <p class="mb-1 font-weight-bold">{{ __('Description :') }} </p>
                            <div class=" mb-3">
                                <textarea name="description" id="description" rows="6" class="form-control bapric_edittor"
                                    placeholder="{{ __('Attorney description here') }}">{!! clean($attorney ? $attorney->description : old('description')) !!}</textarea>
                                <br>
                                @if ($errors->has('description'))
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                @endif
                            </div>


                            <p class="mb-1 font-weight-bold">{{ __('Education : ') }}</p>
                            <div class=" mb-3">
                                <textarea name="education" id="education" rows="6" class="form-control bapric_edittor"
                                    placeholder="{{ __('Attorney education here') }}">{!! clean($attorney ? $attorney->education : old('education')) !!}</textarea>
                                <br>
                                @if ($errors->has('education'))
                                    <p class="text-danger">{{ $errors->first('education') }}</p>
                                @endif
                            </div>

                            <p class="mb-1 font-weight-bold">{{ __('Professional Experience :') }} </p>
                            <div class=" mb-3">
                                <textarea name="professional_exp" id="professional_exp" rows="6"
                                    class="form-control bapric_edittor"
                                    placeholder="{{ __('Attorney professional_exp here') }}">{!! clean($attorney ? $attorney->professional_exp : 'professional experience') !!}</textarea>
                                <br>
                                @if ($errors->has('professional_exp'))
                                    <p class="text-danger">{{ $errors->first('professional_exp') }}</p>
                                @endif
                            </div>

                            <p class="mb-1 font-weight-bold">{{ __('Legal Experience :') }} </p>
                            <div class=" mb-3">
                                <textarea name="legal_exp" id="legal_exp" rows="6" class="form-control bapric_edittor"
                                    placeholder="{{ __('Attorney legal_exp here') }}">{!! clean($attorney ? $attorney->legal_exp : old('legal_exp')) !!}</textarea>
                                <br>
                                @if ($errors->has('legal_exp'))
                                    <p class="text-danger">{{ $errors->first('legal_exp') }}</p>
                                @endif
                            </div>

                            <div class=" ">
                                <p class="mb-2 font-weight-bold">{{ __('Image : ') }}
                                    <code>{{ 'Acceptable image size 370 x 336 pixel' }}</code>
                                </p>
                                <div class="mb-3">
                                    <div class="partner_bg_image" id="partner_bg_image">
                                        <div class="input-images"></div>
                                    </div>
                                </div>
                            </div>

                            @if ($attorney)
                                <div class="pr-2 ">
                                    <p class="mb-2 font-weight-bold ">{{ __('Old Image :') }} </p>
                                    <img src="{{ asset('upload/attorneys/' . $attorney->image) }}" alt="" width="200"
                                        class="img-thumbnail bg-secondary">
                                </div>
                            @endif


                        </div>
                        <div class="card-footer">
                            <div class="wizard-action text-left">
                                <button class="btn btn-wave-light btn-danger btn-lg"
                                    type="submit">{{ __('Submit form') }}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('backend.pages.attorney.internal-assets.js.attorney-page-scripts')
    @include('backend.layouts.message')
@endsection
