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
        <div class="card-body" id="messages-container" style="height: 420px; overflow-y: auto; background:#f8f9fa">

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
              class="card-footer bg-white"
              id="chat-form">
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

<script>
const chatId = {{ $chat->id }};
let lastMessageId = {{ $messages->last()?->id ?? 0 }};

function fetchNewMessages() {
    fetch(`/admin/chats/${chatId}/messages`)
        .then(response => response.json())
        .then(messages => {
            let hasNewMessages = false;
            messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    const isOwn = msg.sender_id === {{ auth()->id() }};
                    const messageHtml = `
                        <div class="mb-3 d-flex ${isOwn ? 'justify-content-end' : ''}">
                            <div class="p-3 rounded-3 shadow-sm"
                                 style="max-width: 70%;
                                        background: ${isOwn ? '#0d6efd' : '#ffffff'};
                                        color: ${isOwn ? '#fff' : '#000'}">

                                <div class="small fw-semibold mb-1">
                                    ${msg.sender.name}
                                </div>

                                <div>
                                    ${msg.message}
                                </div>

                                <div class="small text-end opacity-75 mt-1">
                                    ${new Date(msg.created_at).toLocaleString()}
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('messages-container').innerHTML += messageHtml;
                    lastMessageId = msg.id;
                    hasNewMessages = true;
                }
            });
            // Scroll to bottom if new messages
            if (hasNewMessages) {
                const container = document.getElementById('messages-container');
                container.scrollTop = container.scrollHeight;
            }
        });
}

// Poll every 2 seconds
setInterval(fetchNewMessages, 2000);

// Handle form submission
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(this.action, {
        method: 'POST',
        body: formData
    }).then(() => {
        document.querySelector('input[name="message"]').value = '';
        // Fetch messages immediately after sending
        setTimeout(fetchNewMessages, 100);
    });
});
</script>
@endsection
