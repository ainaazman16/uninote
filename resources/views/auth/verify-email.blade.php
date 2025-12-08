@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-6">

        <div class="card shadow-sm">
            <div class="card-body p-4 text-center">

                <h3 class="mb-3">Verify Your Email</h3>

                <p class="text-muted mb-4">
                    Before continuing, please check your email for a verification link.
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button class="btn btn-primary w-100 mb-3">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger w-100">
                        Logout
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
