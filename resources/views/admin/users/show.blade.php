@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="fw-bold mb-3">User Profile</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                    class="rounded mb-3"
                    width="120">
            @endif

            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Student ID:</strong> {{ $user->id ?? '-' }}</p>
            <p><strong>University:</strong> {{ $user->university ?? '-' }}</p>
            <p><strong>Programme:</strong> {{ $user->programme ?? '-' }}</p>
            <p><strong>Year of Study:</strong> {{ $user->year_of_study ?? '-' }}</p>
            <p><strong>Bio:</strong><br>{{ $user->bio ?? '-' }}</p>

            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
                Back
            </a>

        </div>
    </div>
        <hr>

<h5 class="fw-bold mt-4">Provider Application Details</h5>

@if($user->providerApplication)

    <div class="mt-3">
        <p><strong>Application Status:</strong>
            <span class="badge
                @if($user->providerApplication->status === 'pending') bg-warning
                @elseif($user->providerApplication->status === 'approved') bg-success
                @else bg-danger @endif">
                {{ ucfirst($user->providerApplication->status) }}
            </span>
        </p>

        <p><strong>Applied On:</strong>
            {{ $user->providerApplication->created_at->format('d M Y, H:i') }}
        </p>

        <p><strong>Reason / Motivation:</strong></p>
        <div class="border rounded p-3 bg-light">
            {{ $user->providerApplication->reason }}
        </div>

        <p><strong>Additional Notes:</strong></p>
        <div class="border rounded p-3 bg-light">
            {{ $user->providerApplication->background_info ?? 'N/A' }}
        </div>

    </div>

@else
    <p class="text-muted mt-3">
        This user has not submitted a provider application.
    </p>
@endif

</div>
@endsection
