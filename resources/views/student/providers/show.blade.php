@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex align-items-center gap-4">

            <img
                src="{{ $user->profile_photo 
                    ? asset('storage/'.$user->profile_photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                class="rounded-circle"
                width="100"
                height="100"
            >

            <div class="flex-grow-1">
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-1">
                    {{ $user->university }} · {{ $user->programme }}
                </p>
                <p class="small mb-0">{{ $user->bio }}</p>
            </div>

            <div>
                @if($isSubscribed)
                    <button class="btn btn-success mb-1" disabled>
                        ✔ Subscribed
                    </button>

                    <span class="badge bg-success mb-2">
                        {{ $daysRemaining }} days remaining
                    </span>

                    <form method="POST"
                        action="{{ route('subscriptions.cancel', $subscription->id) }}"
                        onsubmit="return confirm('Cancel this subscription?');">
                        @csrf
                        <button class="btn btn-danger btn-sm">
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

            </div>

        </div>
    </div>

    <h5 class="fw-bold mb-3">Notes by {{ $user->name }}</h5>

    <div class="row g-3">
        @forelse($notes as $note)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $note->title }}</h6>
                        <p class="text-muted small">{{ $note->subject }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No notes uploaded yet.</p>
        @endforelse
    </div>

</div>
@endsection
