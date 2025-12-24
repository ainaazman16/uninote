@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
                    @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

            <div class="card shadow-sm">
                <div class="card-body text-center p-4">

                    {{-- Profile Picture --}}
                    <img src="{{ $user->profile_photo
                        ? asset('storage/'.$user->profile_photo)
                        : asset('images/default-avatar.png') }}"
                        class="rounded-circle mb-3"
                        width="120" height="120">

                    <h3 class="fw-bold">{{ $user->name }}</h3>

                    <span class="badge bg-secondary text-uppercase mb-3">
                        {{ $user->role }}
                    </span>

                    <hr>

                    <p><strong>University:</strong> {{ $user->university ?? '-' }}</p>
                    <p><strong>Programme:</strong> {{ $user->programme ?? '-' }}</p>
                    <p><strong>Year:</strong> {{ $user->year_of_study ?? '-' }}</p>

                    <p class="text-muted mt-3">
                        {{ $user->bio ?? 'No bio provided.' }}
                    </p>
                    <div class="mt-4">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                               class="btn btn-primary me-2">
                                Edit Profile
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}"
                           class="btn btn-outline-secondary">
                            Back to Dashboard
                        </a>
                    </div>
                    {{-- Provider Stats --}}
                    @if($user->role === 'provider')
                        <hr>
                        <p><strong>Total Notes:</strong> {{ $user->notes()->count() }}</p>
                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
