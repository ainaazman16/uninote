@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.chats') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Back to Chats
        </a>

        <h5 class="fw-bold mb-0">
            Chat with {{ $chat->provider->name }}
        </h5>
    </div>

    <div class="card shadow-sm border-0">
        {{-- Messages --}}
        <div class="card-body" style="height: 420px; overflow-y: auto; background:#f8f9fa">

            @foreach($messages as $msg)
                <div class="mb-3 d-flex {{ $msg->sender_id === auth()->id() ? 'justify-content-end' : '' }}">
                    <div class="p-3 rounded-3 shadow-sm"
                         style="max-width: 70%;
                                background: {{ $msg->sender_id === auth()->id() ? '#0d6efd' : '#ffffff' }};
                                color: {{ $msg->sender_id === auth()->id() ? '#fff' : '#000' }}">

                        <div class="small fw-semibold mb-1">
                            {{ $msg->sender->name }}
                        </div>

                        <div>
                            {{ $msg->message }}
                        </div>

                        <div class="small text-end opacity-75 mt-1">
                            {{ $msg->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Send message --}}
        <form method="POST"
              action="{{ route('admin.chats.send', $chat) }}"
              class="card-footer bg-white">
            @csrf

            <div class="input-group">
                <input type="text"
                       name="message"
                       class="form-control"
                       placeholder="Type your reply..."
                       required>

                <button class="btn btn-primary">
                    Send
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
