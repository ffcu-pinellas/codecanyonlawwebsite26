@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <div class="main-wrapper">
        <div class="container mt-2 mb-3">
            <div class="col-md-12">
                <a href="javascript:history.go(-1)" class="float-right">
                    <button type="button" class="btn btn-theme btn-sm rounded"><span class="h3 font-weight-bold text-gold">&#8617;</span></button></a>
            </div>
        </div>

        <div class="container my-5">
            <small class="text-gold"><i>{{ __('Messages') }}</i></small>
            <div class="col-11 mx-auto message-content">
                @foreach($conversation->messages as $message)
                    @if($message->user->id == Auth::user()->id)
                        <div class="card mb-2">
                            <div class="card-header" style="background-color: #b1986f;">
                                <div class="row">
                                    <div class="col-9">
                                        <p class="font-weight-bold text-light">
                                            {!! $message->file?'<i class="fas fa-file-pdf"></i> &nbsp;'.$message->file_name.'&nbsp;<a href="'.route('download.chatting-file',$message->id).'" class="text-light" target="_blank"><i class="fas fa-cloud-download-alt"></i></a>':'' !!}
                                        </p>
                                        <p class="font-weight-bold text-light">{{ clean($message->text) }}</p>
                                    </div>
                                    <div class="col-3"><img src="{{ asset($message->user->profile_photo_url) }}" alt="{{ $message->user->name }}" class="img-fluid img-thumbnail chat-attorney-Search float-right"></div>
                                    <div class="col-12"><p class="font-weight-bold text-light">{{ date('H:i',strtotime($message->created_at)) }}, {{ strtotime(date('Y-m-d',strtotime($message->created_at))) === date('Y-m-d',time()) ? 'Today':date('Y-m-d',strtotime($message->created_at)) }}</p></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card mb-2">
                            <div class="card-header" style="background-color: #0b1d37;">
                                <div class="row">
                                    <div class="col-3"><img src="{{ asset($message->user->profile_photo_url) }}" alt="{{ $message->user->name }}" class="img-fluid img-thumbnail chat-attorney-Search float-left"></div>
                                    <div class="col-9">
                                        <p class="font-weight-bold text-light">
                                            {!! $message->file?'<i class="fas fa-file-pdf"></i> &nbsp;'.$message->file_name.'&nbsp;<a href="'.route('download.chatting-file',$message->id).'" class="text-light" target="_blank"><i class="fas fa-cloud-download-alt"></i></a>':'' !!}
                                        </p>
                                        <p class="font-weight-bold text-light">{{ clean($message->text) }}</p>
                                    </div>
                                    <div class="col-12 text-right"><p class="font-weight-bold text-light">{{ date('H:i',strtotime($message->created_at)) }}, {{ strtotime(date('Y-m-d',strtotime($message->created_at))) === date('Y-m-d',time()) ? 'Today':date('Y-m-d',strtotime($message->created_at)) }}</p></div>
                                </div>
                            </div>
                        </div>
                    @endif


                @endforeach
            </div>
            <div class="col-11 mx-auto">
                <form action="{{ route('client.conversation.send-message', $conversation->slug) }}" method="post" enctype="multipart/form-data" id="chatForm">
                    @csrf
                    <input type="text" name="text" id="chatTaxtInput" class="form-control" placeholder="Write your text.">
                    <input type="file" hidden name="file" id="chatFileInput" class="form-control">
                    <label for="chatFileInput" class="chatFileInputLabel"><i class="fas fa-paperclip"></i></label>
                    <button type="submit" class="btn btn-gold btn-sm text-light"><i class="fas fa-paper-plane"></i></button>
                    <p class="text-right" id="showSelectedFile"></p>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('backend/assets/js/file-name-view.js') }}"></script>
@endsection
