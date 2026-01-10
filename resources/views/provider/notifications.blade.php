@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3 class="fw-bold mb-4">Notifications</h3>

        @forelse($notifications as $notification)
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">{{ $notification->data['title'] }}</h6>
                    <p class="mb-2">{{ $notification->data['message'] }}</p>

                    @if (isset($notification->data['proof_url']))
                        <a href="{{ $notification->data['proof_url'] }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            View Payment Proof
                        </a>
                    @endif

                    <small class="text-muted d-block mt-2">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        @empty
            <p class="text-muted">No notifications.</p>
        @endforelse
    </div>
@endsection
