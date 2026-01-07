@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Dashboard
        </a>
    </div>
    <div class="container py-4">

        <h3 class="fw-bold mb-4">My Subscriptions</h3>

        @if ($subscriptions->isEmpty())
            <div class="alert alert-info">
                You are not subscribed to any provider yet.
            </div>
        @endif

        <div class="row g-3">

            @foreach ($subscriptions as $subscription)
                @php
                    $provider = $subscription->provider;
                    $user = $provider->user;
                    $daysRemaining = max(0, now()->diffInDays($subscription->expiresAt(), false));
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0 rounded-4">

                        <div class="card-body d-flex gap-3">

                            {{-- Profile photo --}}
                            <img src="{{ $user->profile_photo
                                ? asset('storage/' . $user->profile_photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                class="rounded-circle" width="60" height="60">

                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    {{ $user->name }}
                                </h6>

                                <p class="text-muted small mb-1">
                                    {{ $user->university }} · {{ $user->programme }}
                                </p>

                                <span class="badge bg-success">
                                    {{ (int) round($daysRemaining) }} days remaining
                                </span>
                            </div>

                        </div>

                        <div class="card-footer bg-white border-0 d-flex gap-2">
                            <a href="{{ route('student.providers.show', $user->id) }}"
                                class="btn btn-outline-primary btn-sm w-100">
                                View Provider
                            </a>

                            <form method="POST" action="{{ route('subscriptions.cancel', $subscription->id) }}"
                                onsubmit="return confirm('Cancel this subscription?')">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm w-100">
                                    Cancel
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection
