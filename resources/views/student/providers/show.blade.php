@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">

                    {{-- Profile Picture --}}
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             class="rounded-circle mb-3"
                             width="120" height="120">
                    @endif

                    <h4 class="fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-1">{{ $user->programme }}</p>
                    <p class="text-muted">{{ $user->university }}</p>

                    <hr>

                    <div class="text-start">
                        <p><strong>Year of Study:</strong> {{ $user->year_of_study }}</p>
                        <p><strong>About:</strong><br>
                           {{ $user->bio ?? 'No bio provided.' }}
                        </p>
                    </div>

                    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
                        Back
                    </a>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
