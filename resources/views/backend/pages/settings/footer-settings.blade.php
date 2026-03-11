@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
    @include('backend.pages.settings.page-settings.internal-assets.css.footer-settings-css')
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

        <!-- Top Footer -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light bg-white text-light rounded">
                    <div class="card-header bg-dark expand-btn">
                        <div class="col-6">
                            <span class="card-title font-weight-bold">{{ __('Top Footer') }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <i class="fas fa-chevron-circle-down fa-2x"></i>
                        </div>
                    </div>
                    <form action="{{ route('admin.settings.footer.store') }}" method="post" class=""
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page" value="footer">
                        <input type="hidden" name="group" value="top_footer">
                        <div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">{{ __('Show') }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show"
                                                           name="show" {{ $footer?($footer->show?'checked':''):'' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row d-none">
                                <div class="card col-md-3 p-3">
                                    <div class="card-header">
                                        <h6 class="text-center font-weight-bold"><u>{{ __('Column1') }}</u></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group text-center">
                                            <img src="{{$footer?$footer->footer_logo:'' }}" alt=""
                                                 class="img-thumbnail w-100 bg-dark">
                                        </div>
                                        <div class="form-group">
                                            <div class="footer-logo" id="footer_logo">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="column1_short_disc"
                                                   class="card-title">{{ __('Column1 Short Description') }}</label>
                                            <input type="text" name="column1_short_disc" id="column1_short_disc"
                                                   class="form-control"
                                                   value="{{$footer?$footer->column1_short_disc:'' }}">
                                        </div>
                                        <div class="form-group">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td class="text-right font-weight-bold">{{ __('Show Social Icons') }}</td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   name="show_social" {{ $footer?($footer->show_social?'checked':''):'' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card col-md-3 p-3">
                                    <div class="card-header">
                                        <h6 class="text-center font-weight-bold"><u>{{ __('Column2') }}</u></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mt-5">
                                            <label for="column2_recent_post_title"
                                                   class="card-title">{{ __('Column2 recent post title') }}</label>
                                            <input type="text" name="column2_recent_post_title"
                                                   id="column2_recent_post_title" class="form-control"
                                                   value="{{ $footer?$footer->column2_recent_post_title:'' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="column2_recent_post"
                                                   class="card-title">{{ __('Column2 recent post number') }}</label>
                                            <input type="number" name="column2_recent_post_number"
                                                   id="column2_recent_post_number" class="form-control"
                                                   value="{{ $footer?$footer->column2_recent_post_number:'' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card col-md-3 p-3">
                                    <div class="card-header">
                                        <h6 class="text-center font-weight-bold"><u>{{ __('Column3') }}</u></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mt-5">
                                            <label for="column3_popular_post_title"
                                                   class="card-title">{{ __('Column3 popular post title') }}</label>
                                            <input type="text" name="column3_popular_post_title"
                                                   id="column3_popular_post_title" class="form-control"
                                                   value="{{ $footer?$footer->column3_popular_post_title:'' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="column3_popular_post_title_number"
                                                   class="card-title">{{ __('Column3 popular post number') }}</label>
                                            <input type="number" name="column3_popular_post_title_number"
                                                   id="column3_popular_post_title_number" class="form-control"
                                                   value="{{ $footer?$footer->column3_popular_post_title_number:'' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card col-md-3 p-3">
                                    <div class="card-header">
                                        <h6 class="text-center font-weight-bold"><u>{{ __('Column4') }}</u></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="column4_title"
                                                   class="card-title">{{ __('Column4 title') }}</label>
                                            <input type="text" name="column4_title" id="column4_title"
                                                   class="form-control" value="{{ $footer?$footer->column4_title:'' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="column4_description"
                                                   class="card-title">{{ __('Column4 Description') }}</label>
                                            <textarea name="column4_description" id="column4_description"
                                                      class="form-control"
                                                      rows="8">{!! clean($footer?$footer->column4_description:'') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">{{__('Footer Copy Right')}}: </p>
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" name="footer_copy_right" class="form-control" aria-label="Large"
                                       aria-describedby="inputGroup-sizing-sm"
                                       placeholder="{{__('Footer Copy Right')}}"
                                       value="{{$footer?clean($footer->footer_copy_right):''}}">
                                <br>
                                @if ($errors->has('footer_copy_right'))
                                    <span class="text-danger">{{ $errors->first('footer_copy_right') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer d-none">
                            <button type="submit" class="btn btn-danger btn-lg rounded">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/page-section-settings/footer-script.js') }}"></script>
    @include('backend.layouts.message')
@endsection
