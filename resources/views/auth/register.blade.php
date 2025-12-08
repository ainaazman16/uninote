@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-5">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Create an Account</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>

                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>

                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>

                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control" required>
                    </div>

                    <!-- Register Button -->
                    <button class="btn btn-primary w-100 py-2">
                        Register
                    </button>

                </form>

                <hr>

                <div class="text-center">
                    <a href="{{ route('login') }}">
                        Already have an account? Login here
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
