@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('dashboard') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Dashboard
    </a>
    </div>
    <div class="container py-4">

        <h3 class="fw-bold mb-4">Transaction History</h3>

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

                                    {{-- Date --}}
                                    <td>
                                        {{ $tx->created_at->format('d M Y') }}<br>
                                        <small class="text-muted">
                                            {{ $tx->created_at->format('h:i A') }}
                                        </small>
                                    </td>

                                    {{-- Description --}}
                                    <td>
                                        @if ($tx->description)
                                            {{ $tx->description }}
                                        @elseif($tx->type === 'topup')
                                            Wallet Top-Up (Approved)
                                        @elseif($tx->type === 'subscription')
                                            Subscription to
                                            <strong>{{ $tx->provider?->user?->name ?? 'Provider' }}</strong>
                                        @elseif($tx->type === 'withdrawal')
                                            Withdrawal
                                        @else
                                            {{ ucfirst($tx->type) }}
                                        @endif
                                    </td>

                                    {{-- Type --}}
                                    <td>
                                        <span
                                            class="badge
                @if ($tx->type === 'topup') bg-success
                @elseif($tx->type === 'subscription') bg-primary
                @elseif($tx->type === 'withdrawal') bg-danger
                @else bg-secondary @endif">
                                            {{ ucfirst($tx->type) }}
                                        </span>
                                    </td>

                                    {{-- Amount --}}
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
