@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('dashboard') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Dashboard
    </a>
    </div>
    <div class="container py-4">

        <h3 class="fw-bold mb-4">My Wallet</h3>

        {{-- Wallet Balance --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Current Balance</h6>
                        <h2 class="fw-bold text-success">
                            RM {{ number_format($wallet?->balance ?? 0, 2) }}
                        </h2>

                        <a href="{{ route('student.wallet.topup.form') }}" class="btn btn-primary mt-3">
                            + Top Up Wallet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Pending Top-Ups --}}
        @if (isset($pendingTopups) && $pendingTopups->count())
            <h5 class="fw-bold mb-3">Pending Top-Ups</h5>

            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingTopups as $topup)
                                <tr>
                                    <td>{{ $topup->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="fw-bold">RM {{ number_format($topup->amount, 2) }}</td>
                                    <td>{{ ucfirst($topup->payment_method) }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            Pending Approval
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Transaction History --}}
        <h5 class="fw-bold mb-3">Transaction History</h5>

        @if ($transactions->count())
            <div class="card shadow-sm border-0 rounded-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $tx)
                                <tr>
                                    <td>
                                        {{ $tx->created_at->format('d M Y') }} <br>
                                        <small class="text-muted">
                                            {{ $tx->created_at->format('h:i A') }}
                                        </small>
                                    </td>

                                    <td>
                                        @if ($tx->type === 'topup')
                                            Wallet Top-Up (Approved)
                                        @elseif($tx->type === 'subscription')
                                            Subscription to {{ $tx->provider?->user?->name ?? 'Provider' }}
                                        @elseif($tx->type === 'withdrawal')
                                            Withdrawal
                                        @else
                                            {{ ucfirst($tx->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        <span
                                            class="badge
                                        @if ($tx->type === 'topup') bg-success
                                        @elseif($tx->type === 'withdrawal') bg-danger
                                        @else bg-info @endif
                                    ">
                                            {{ ucfirst($tx->type) }}
                                        </span>
                                    </td>

                                    <td
                                        class="text-end fw-bold
                                    {{ $tx->type === 'topup' ? 'text-success' : 'text-danger' }}">
                                        {{ $tx->type === 'topup' ? '+' : '-' }}
                                        RM {{ number_format($tx->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="text-muted">No transactions yet.</p>
        @endif

    </div>
@endsection
