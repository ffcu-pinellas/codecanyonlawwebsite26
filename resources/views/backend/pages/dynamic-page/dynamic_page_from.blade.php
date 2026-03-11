@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')
@endsection

@section('content')
    <div id="wrapper-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <nav
                        class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                        <a class="breadcrumb-item text-white" href="#">{{ __('Home') }}</a>
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
                        <div class="card-body ">
                            <form action="{{ route('admin.dynamic-page.page-store',$page? $page->slug:'') }}"
                                  method="post" id="pageForm" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="pageTitle">
                                                <span class="d-block card-title mb-1">{{__('Page Title')}} </span>
                                                @error('page_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </label>
                                            <input type="text" name="page_title" id="pageTitle" class="form-control"
                                                   required autofocus autocomplete="true"
                                                   placeholder="{{__('Page title...')}}"
                                                   value="{{ __($page? $page->title:'') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="page-sub-title">{{__('Page Sub-Title')}}</label>
                                            <input type="text" name="sub_title" id="page-sub-title" class="form-control"
                                                   placeholder="{{__('Page Sub Title')}}"
                                                   value="{{ __($page? $page->sub_title:'') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="pageContent">
                                                <span class="d-block card-title mb-1">{{__('Page Content')}} </span>
                                                @error('page_content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }} </strong>
                                                </span>
                                                @enderror
                                            </label>
                                            <textarea name="page_content" id="pageContent" rows="15"
                                                      class="form-control"
                                                      placeholder="{{__('Page Content (HTML Tag Supported)')}}">{!! clean($page? $page->page_content:'') !!}</textarea>
                                        </div>
                                        <p class="mb-1">{{__('Meta Keyword')}}:
                                            <code>{{__('Put comma(,) for separate the meta key')}}</code></p>
                                        <div class="input-group input-group-lg mb-3">
                                            <input type="text" name="meta_keyword" class="form-control"
                                                   aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                                   placeholder="{{__('Meta Keyword')}}" data-role="tagsinput"
                                                   value="{{ $page? $page->meta_keyword:'' }}">
                                            <br>
                                            @if ($errors->has('meta_keyword'))
                                                <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                                            @endif
                                        </div>

                                        <p class="mb-1">{{__("Meta Description")}}:
                                            <code>{{__('maximum 50 word')}}</code></p>
                                        <div class="input-group mb-3">
                            <textarea class="form-control" name="meta_description" aria-label="With textarea"
                                      rows="4" placeholder="{{__('Meta Description')}}">{!! clean($page? $page->meta_description:'') !!}</textarea>
                                            <br>
                                            @if ($errors->has('meta_description'))
                                                <span
                                                    class="text-danger">{{ $errors->first('meta_description') }}</span>
                                            @endif
                                        </div>


                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-row mb-3">
                                            <div class="col-12">
                                                <table class="table table-responsive-sm">
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <label for="programStatus">
                                                                <span
                                                                    class="card-title">{{ __('Publish status') }}</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <label class="switch float-left">
                                                                <input type="checkbox" name="status"
                                                                       {{ $page?($page->status?'checked':''):'checked' }}
                                                                       id="programStatus">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                @if($page != null)
                                                    @if($page->delete_able)
                                                        <button type="button" class="btn btn-primary w-100 btn-lg my-2"
                                                                id="distroyPage">{{__('Destroy Page')}}</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        @if($page)
                                            @if($page->status)
                                                <div class="form-row">
                                                    <div class="col-12">
                                                        <div class="card card-justify">
                                                            <div class="card-body">
                                                                <label for="pageSlag"><span
                                                                        class="card-title text-black">{{__('url')}}</span>
                                                                    <br>
                                                                    <i class="text-black icon-md material-icons cursor-pointer"
                                                                       id="contentCopy">content_copy</i>
                                                                    <code>{{ __('Click here to copy this link for use in the menu url') }}</code></label>
                                                                <input type="text" id="copyUrl" class="form-control"
                                                                       value="{!! clean($page?$page->slug:'') !!}"
                                                                       readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="card card-justify">
                                                    <div class="card-body">
                                                        <label for="pageSlag"><span
                                                                class="card-title text-black">{{ __('Slag') }}</span>
                                                            <br><code>{{__("please never put (?)(,)(\" \")(#)(') inside
                                                                slag")}}</code></label>
                                                        <input type="text" name="slug" id="pageSlag"
                                                               class="form-control" value="{{ $page? $page->slug:'' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-3 font-weight-bold">{{__('Background Image')}}:</p>
                                        <div class="form-row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <div class="bg_image " id="bg_image">
                                                        <div class="input-images"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="img-favicon">
                                                @if(!empty($page))
                                                    @if(!empty($page->breadcrumb_bg))
                                                        <img src="{{ asset($page?$page->breadcrumb_bg:'' )}}"
                                                             alt="Og Meta Image" class="img-thumbnail img-fluid">
                                                    @endif
                                                @endif
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!-- layout selection -->

                                <button type="submit"
                                        class="btn btn-danger w-100 btn-lg my-2"> {{ __('Save Page') }} </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            @if($page != null)
                @if($page->delete_able)
                    <form action="{{ route('admin.dynamic-page.destroy-page',$page->slug) }}" method="get"
                          id="deletePageForm"></form>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/page-form-scripts.js') }}"></script>
    @include('backend.pages.dynamic-page.internal-assets.js.bg-image-page-scripts')
    @include('backend.layouts.message')
@endsection
