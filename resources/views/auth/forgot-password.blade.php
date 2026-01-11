@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">Forgot Password</h3>

                    <p class="text-muted mb-4">
                        Enter your email address and we will send you a link to reset your password.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('reset_link'))
                        <div class="alert alert-warning">
                            <strong>Development Mode:</strong> Click the link below to reset your password:
                            <hr>
                            <a href="{{ session('reset_link') }}" class="btn btn-sm btn-primary w-100 mt-2">
                                Reset Password Now
                            </a>
                            <small class="d-block mt-2 text-muted">Or copy: <code
                                    class="text-dark">{{ session('reset_link') }}</code></small>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email', absolute: false) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>

                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button class="btn btn-primary w-100 py-2">
                            Send Reset Link
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
