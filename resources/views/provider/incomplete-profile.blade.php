@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body text-center p-4">

                    <h4 class="fw-bold mb-3">Complete Your Profile First</h4>

                    <p class="text-muted">
                        To apply as a note provider, you must complete your profile
                        including academic details and a profile picture.
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-primary mt-3">
                        Go to Profile
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="btn btn-outline-secondary mt-2">
                        Back to Dashboard
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
