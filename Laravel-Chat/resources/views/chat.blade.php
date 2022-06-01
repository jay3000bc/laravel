<!-- resources/views/chat.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
        <!-- <video-chat :allusers="{{ $users }}" :authUserId="{{ auth()->id() }}" turn_url="{{ env('TURN_SERVER_URL') }}"
        turn_username="{{ env('TURN_SERVER_USERNAME') }}" turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" /> -->
        
        <!-- <chat-messages :messages="messages" :user="{{ Auth::user()->id }}" :chat_user="{{$chat_user}}"></chat-messages> -->
        </div>
        <div class="card-footer">
            <chat-form v-on:messagesent="addMessage" :user="{{ Auth::user() }}" :chat_user="{{$chat_user}}" :messages1="messages" 
            :allusers="{{$allusers}}" turn_url="{{ env('TURN_SERVER_URL') }}"
            turn_username="{{ env('TURN_SERVER_USERNAME') }}" turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}"></chat-form>
        </div>
    </div>
</div>
@endsection