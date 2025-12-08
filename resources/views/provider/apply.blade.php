@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-6">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Apply as Note Provider</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($application)
                    <div class="alert alert-info">
                        Your application status:
                        <strong class="text-uppercase">{{ $application->status }}</strong>
                    </div>

                    @if($application->status == 'rejected')
                        <p class="text-danger">You may submit a new application.</p>
                    @else
                        <p class="text-muted">You cannot edit while waiting for approval.</p>
                        @if($application->status == 'pending')
                            <a href="{{ url('/dashboard') }}" class="btn btn-secondary w-100">Back</a>
                        @endif
                        @if($application->status == 'approved')
                            <a href="{{ url('/provider/dashboard') }}" class="btn btn-primary w-100">Go to Provider Dashboard</a>
                        @endif
                        @endif
                    @else
                    <!-- No application yet -->
                @endif

                @if(!$application || $application->status == 'rejected')
                <form method="POST" action="{{ route('provider.apply.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Why do you want to become a provider?</label>
                        <textarea name="reason" rows="4"
                                  class="form-control @error('reason') is-invalid @enderror"
                                  required>{{ old('reason') }}</textarea>

                        @error('reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100 py-2">
                        Submit Application
                    </button>
                </form>
                @endif

            </div>
        </div>

    </div>
</div>

@endsection
