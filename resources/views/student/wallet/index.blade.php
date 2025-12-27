@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-4">My Wallet</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <h6 class="text-muted">Current Balance</h6>
            <h2 class="fw-bold">RM {{ number_format($wallet->balance, 2) }}</h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Top Up Wallet</h5>

            <form method="POST" action="{{ route('wallet.topup') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Amount (RM)</label>
                    <input type="number" name="amount" class="form-control" min="5" required>
                </div>

                <button class="btn btn-primary w-100">
                    Top Up
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
