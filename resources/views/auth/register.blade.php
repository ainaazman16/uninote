@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #6A11CB 0%, #2575FC 100%);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    }

    .auth-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        padding: 40px;
        width: 450px;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .brand-title {
        font-weight: 700;
        font-size: 32px;
        color: #2575FC;
    }

    .sub-text {
        color: #6c757d;
        font-size: 15px;
    }

    .btn-primary {
        background-color: #2575FC;
        border: none;
        font-weight: 600;
        font-size: 16px;
        padding: 12px;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background-color: #1f63d6;
    }

    .form-control {
        padding: 12px 15px;
        border-radius: 10px;
    }
</style>


<div class="auth-card">

    <div class="text-center mb-4">
        <h2 class="brand-title">UniNote</h2>
        <p class="sub-text">Join the hive and start learning smarter.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Full Name --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" name="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Enter your full name"
                value="{{ old('name') }}" required>

            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Enter your email"
                value="{{ old('email') }}" required>

            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Create a password"
                required>

            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="form-control"
                placeholder="Re-enter your password"
                required>
        </div>

        {{-- Register Button --}}
        <button class="btn btn-primary w-100">Register</button>

    </form>

    <hr class="my-4">

    <div class="text-center">
        <span class="text-muted">Already have an account?</span>
        <a href="{{ route('login') }}" class="fw-semibold ms-1">Log In</a>
    </div>

</div>

@endsection
