@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel').' | '.$title)

@section('page-css')

@endsection

@section('content')

<div id="wrapper-content">

    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
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
                        <div class="col-md-6 col-sm-12 text-right">
                            <button type="button" id="addBlogCategoryBtn" class="btn btn-danger btn-sm rounded"><i class="material-icons">add</i></button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive style-scroll">
                        <table id="example" class="table bapric_table table-striped table-bordered miw-500" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="10%">{{__('SL')}}</th>
                                <th width="60%">{{__('Name')}}</th>
                                <th width="20%">{{__('Status')}}</th>
                                <th width="10%">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{$category->name}}</td>
                                    <td> </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="javascript:void(0)">
                                                <button type="button" class="btn btn-sm btn btn-success m-1 blogCategoryEditBtn" data-id="{{ $category->id }}">{{ __('Edit') }}</button>
                                            </a>

                                            <a href="javascript:void(0)" title="{{__('Delete')}}" class="deleteRow">
                                                <button type="button" class="btn btn-sm btn btn-danger m-1">{{__('Delete')}}</button>
                                                <form action="{{ route('admin.blog.category.destroy', $category->id) }}" method="post" class="deleteForm">
                                                    @csrf
                                                    @method('delete')
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

    <!-- Blog Category Modal -->
    <div class="modal fade" id="blogCategoryModal" tabindex="-1" role="dialog" aria-labelledby="blogCategoryModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title card-title" id="blogCategoryModalLongTitle">{{ __('Add new category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.blog.category.store') }}" method="post" id="blogCategoryForm">
                    @csrf
                    <div class="modal-body">
                        <p class="card-title"><label for="name">{{ __('Category Name') }}</label></p>
                        <div class="form-group">
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
    @include('backend.pages.blogs.internal-assets.js.delete-warning')
    <script src="{{asset('backend/assets/js/blog-category.js')}}"></script>
    @include('backend.layouts.message')
@endsection

