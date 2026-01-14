@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">Notifications</h4>

    @forelse(auth()->user()->notifications as $notification)
        <div class="card mb-3">
            <div class="card-body">
                <strong>{{ $notification->data['title'] }}</strong>
                <p class="mb-1">
                    {{ $notification->data['message'] }}
                </p>

                <a href="{{ route('admin.notes.index') }}"
                   class="btn btn-sm btn-primary">
                    Review Note
                </a>
            </div>
        </div>
    @empty
        <p class="text-muted">No notifications.</p>
    @endforelse

</div>
@endsection
