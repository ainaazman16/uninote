@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- LEFT: PROFILE INFO -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">

                    {{-- Profile Photo --}}
                    @if($provider->profile_photo)
                        <img src="{{ asset('storage/' . $provider->profile_photo) }}"
                             class="rounded-circle mb-3"
                             width="120"
                             height="120"
                             style="object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($provider->name) }}"
                             class="rounded-circle mb-3"
                             width="120">
                    @endif

                    <h4 class="fw-bold">{{ $provider->name }}</h4>
                    <p class="text-muted">{{ $provider->email }}</p>

                    @if($provider->university)
                        <p class="mb-1"><strong>University:</strong> {{ $provider->university }}</p>
                    @endif

                    @if($provider->programme)
                        <p class="mb-1"><strong>Programme:</strong> {{ $provider->programme }}</p>
                    @endif

                    @if($provider->bio)
                        <p class="mt-3 text-muted">{{ $provider->bio }}</p>
                    @endif

                </div>
            </div>
        </div>

        <!-- RIGHT: ACTIONS & NOTES -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Subscription</h5>

                    @php
                        $isSubscribed = auth()->user()
                            ->subscriptions()
                            ->where('provider_id', $provider->id)
                            ->exists();
                    @endphp

                    @if($isSubscribed)
                        <div class="alert alert-success">
                            ✅ You are subscribed to this provider
                        </div>
                    @else
                        <p>
                            Subscribe to access this provider’s notes.
                        </p>

                        <p class="fw-bold">
                            Price: 10 credits
                        </p>

                        <p>
                            Your balance:
                            <strong>{{ auth()->user()->wallet->balance ?? 0 }} credits</strong>
                        </p>

                        <form action="{{ route('subscriptions.store', $provider) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary">
                                Subscribe Now
                            </button>
                        </form>
                    @endif

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">Uploaded Notes</h5>

                    @if(isset($notes) && $notes->count())
                        <ul class="list-group">
                            @foreach($notes as $note)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $note->title }}</span>

                                    @if($isSubscribed)
                                        <a href="{{ route('notes.show', $note) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">
                                            Subscribe to unlock
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No notes uploaded yet.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
