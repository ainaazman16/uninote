@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back
        </a>
    </div>
    <div class="container py-4">

        {{-- Provider profile --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex align-items-center gap-4">

                <img src="{{ $user->profile_photo
                    ? asset('storage/' . $user->profile_photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                    class="rounded-circle" width="100" height="100">

                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>

                    

                    <p class="text-muted mb-1">
                        {{ $user->university }} · {{ $user->programme }}
                    </p>
                    <p class="small mb-0">{{ $user->bio }}</p>
                </div>

                <div class="text-end">
                    @if ($user->id === auth()->id())
                        <span class="badge bg-secondary">Your Profile</span>
                    @elseif ($isSubscribed)
                        <button class="btn btn-success mb-1" disabled>
                            ✔ Subscribed
                        </button>

                        <div>
                            <span class="badge bg-success">
                                {{ (int) round($daysRemaining) }} days remaining
                            </span>
                        </div>

                        <form method="POST" action="{{ route('subscriptions.cancel', $subscription->id) }}"
                            onsubmit="return confirm('Cancel this subscription?');">
                            @csrf
                            <button class="btn btn-danger btn-sm mt-2">
                                Cancel Subscription
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('subscriptions.store', $provider->id) }}">
                            @csrf
                            <button class="btn btn-primary">
                                Subscribe
                            </button>
                        </form>
                    @endif

                    @if ($user->id !== auth()->id())
                        <a href="{{ route('student.provider.chat', $user->id) }}"
                            class="btn btn-outline-primary btn-sm mt-2">
                            Message Provider
                        </a>
                    @endif
                </div>

            </div>
        </div>

        {{-- Notes --}}
        <h5 class="fw-bold mb-3">Notes by {{ $user->name }}</h5>

        <div class="row g-3">
            @forelse($notes as $note)
                <div class="col-md-4">
                    <a href="{{ route('student.notes.show', $note->id) }}" class="text-decoration-none text-dark">

                        <div class="card h-100 shadow-sm note-card">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">
                                    {{ $note->title }}
                                </h6>

                                <p class="text-muted small mb-1">
                                    {{ $note->subject->name ?? 'No subject' }}
                                </p>

                                {{-- Rating stars --}}
                                @php
                                    $avg = round($note->ratings->avg('rating'), 1);
                                    $count = $note->ratings->count();
                                @endphp

                                @if ($count > 0)
                                    <div class="small text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($avg))
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                        <span class="text-muted">
                                            ({{ $avg }}/5 · {{ $count }})
                                        </span>
                                    </div>
                                @else
                                    <div class="small text-muted">
                                        No ratings yet
                                    </div>
                                @endif
                            </div>

                        </div>

                    </a>
                </div>
            @empty
                <p class="text-muted">No notes uploaded yet.</p>
            @endforelse
        </div>

    </div>

    {{-- Small UX hover effect --}}
    <style>
        .note-card {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .note-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }
    </style>
@endsection
