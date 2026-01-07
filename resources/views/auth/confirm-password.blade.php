@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">Confirm Password</h3>

                    <p class="text-muted mb-3">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>

                    <form method="POST" action="{{ route('password.confirm', absolute: false) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>

                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button class="btn btn-primary w-100 py-2">Confirm</button>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
