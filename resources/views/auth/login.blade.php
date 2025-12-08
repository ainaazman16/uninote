@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-5">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Login</h3>

                @if(session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>

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

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <!-- Login Button -->
                    <button class="btn btn-primary w-100 py-2">
                        Login
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>

                <hr>

                <div class="text-center">
                    <a href="{{ route('register') }}">
                        Don’t have an account? Register
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
