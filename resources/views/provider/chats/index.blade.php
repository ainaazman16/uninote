@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">Admin Support Chat</h3>

    <div class="card shadow-sm">
        <div class="card-body" id="messages-container" style="height: 400px; overflow-y:auto">
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

        <form method="POST" action="{{ route('provider.chat.send') }}" class="card-footer" id="chat-form">
            @csrf
            <input type="hidden" name="chat_id" value="{{ $chat->id }}">
            <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                <button class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>

<script>
const chatId = {{ $chat->id }};
let lastMessageId = {{ $messages->last()?->id ?? 0 }};

function fetchNewMessages() {
    fetch(`/support/chat/${chatId}/messages`)
        .then(response => response.json())
        .then(messages => {
            let hasNewMessages = false;
            messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    // Add new message to DOM
                    const isOwn = msg.sender_id === {{ auth()->id() }};
                    const messageHtml = `
                        <div class="mb-2 text-${isOwn ? 'end' : 'start'}">
                            <span class="badge bg-${isOwn ? 'primary' : 'secondary'}">
                                ${msg.sender.name}
                            </span>
                            <div class="mt-1">
                                ${msg.message}
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
