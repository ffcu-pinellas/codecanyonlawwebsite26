@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <div class="main-wrapper">
        <div class="container mt-2 mb-3">
            <div class="col-md-8 mx-auto">
                <form action="{{ route('client.conversation.search-attorney') }}" id="chatSearchForm">
                    <input type="text" name="search" id="searchAttorney" autocomplete="new-password" class="form-control rounded-30 px-5 py-2" placeholder="{{ __('Select your attorney') }}" list="autoSuggestionAttorney" value="{{ request()->search }}">
                    <button type="submit" class="btn btn-theme btn-sm rounded-circle"><i class="icofont-search"></i></button>
                    <datalist id="autoSuggestionAttorney">
                        @foreach($autoSuggestions as $suggestion)
                            <option value="{{ $suggestion->name }}">{{ $suggestion->name }}</option>
                        @endforeach
                    </datalist>
                </form>
            </div>
        </div>

        @if(count($attorneys))
            <div class="container mb-3">
                <small class="text-gold"><i>{{ __('Attorneys') }}</i></small>
                <div class="col-11 mx-auto">
                    @foreach($attorneys as $attorney)
                        @if($attorney->user)
                            <a href="{{ route('client.conversation.start-chat',$attorney->user->id) }}">
                                <div class="card mb-1">
                                    <div class="card-header">
                                        <img src="{{ asset('upload/attorneys/'.$attorney->image) }}" alt="" class="img-fluid img-thumbnail chat-attorney-Search">
                                        {{ $attorney->name }}
                                        <span class="float-right">{{ $attorney->designation?$attorney->designation->name:'NAN' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="container mb-3">
            <small class="text-gold"><i>{{ __('Conversation') }}</i></small>
            <div class="col-11 mx-auto">
                {!! $conversations !!}
            </div>
        </div>
    </div>
@endsection

@section('page-script')

@endsection
