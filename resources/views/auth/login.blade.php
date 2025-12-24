@extends('layouts.app')

@section('content')
<div style="height: 80px;"></div> <!-- space below transparent navbar -->

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
        width: 430px;
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

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 55%;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>

<div class="auth-card">

    <div class="text-center mb-4">
        <h2 class="brand-title">UniNote</h2>
        <p class="sub-text">Your academic hub—anywhere, anytime.</p>
    </div>

    @if(session('status'))
        <div class="alert alert-info">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Enter your email"
                required autofocus>

            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3 position-relative">
            <label class="form-label fw-semibold">Password</label>

            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Enter your password"
                required>

            <span class="toggle-password" onclick="togglePassword()">
                👁️
            </span>

            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button class="btn btn-primary w-100">Log In</button>

    </form>

    <div class="text-center mt-3">
        <a href="{{ route('password.request') }}" class="text-decoration-none">
            Forgot password?
        </a>
    </div>

    <hr>

    <div class="text-center">
        <span class="text-muted">Don't have an account?</span>
        <a href="{{ route('register') }}" class="fw-semibold">Register</a>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

@endsection
