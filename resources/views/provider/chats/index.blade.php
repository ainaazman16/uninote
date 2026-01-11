@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">Admin Support Chat</h3>

    <div class="card shadow-sm">
        <div class="card-body" style="height: 400px; overflow-y:auto">
            @foreach($messages as $msg)
                <div class="mb-2 text-{{ $msg->sender_id === auth()->id() ? 'end' : 'start' }}">
                    <span class="badge bg-{{ $msg->sender_id === auth()->id() ? 'primary' : 'secondary' }}">
                        {{ $msg->sender->name }}
                    </span>
                    <div class="mt-1">
                        {{ $msg->message }}
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('provider.chat.send') }}" class="card-footer">
            @csrf
            <input type="hidden" name="chat_id" value="{{ $chat->id }}">
            <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                <button class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>
@endsection
