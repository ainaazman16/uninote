@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('provider.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
                ← Back to Dashboard
            </a>
            <h3 class="fw-bold mb-0">My Conversations</h3>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Conversation With</th>
                            <th>Type</th>
                            <th>Last Message</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($chats as $chat)
                            <tr>
                                <td class="fw-semibold">
                                    @if ($chat->type === 'provider_admin')
                                        {{ $chat->admin->name ?? 'Admin' }}
                                    @else
                                        {{ $chat->student->name ?? 'Student' }}
                                    @endif
                                </td>

                                <td>
                                    @if ($chat->type === 'provider_admin')
                                        <span class="badge bg-info">Admin Support</span>
                                    @else
                                        <span class="badge bg-primary">Student Chat</span>
                                    @endif
                                </td>

                                <td class="text-muted">
                                    {{ Str::limit(optional($chat->messages->last())->message ?? 'No messages yet', 50) }}
                                </td>

                                <td>
                                    {{ $chat->updated_at->diffForHumans() }}
                                </td>

                                <td class="text-end">
                                    @if ($chat->type === 'provider_admin')
                                        <a href="{{ route('provider.chat') }}" class="btn btn-sm btn-primary">
                                            Open Chat
                                        </a>
                                    @else
                                        <a href="{{ route('provider.chats.show', $chat) }}" class="btn btn-sm btn-primary">
                                            Open Chat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No conversations yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>
@endsection
