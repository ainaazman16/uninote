@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-4">Transaction History</h3>

    @if($transactions->count())
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
                        @foreach($transactions as $tx)
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
                                    @if($tx->type === 'topup')
                                        Wallet Top-Up
                                    @elseif($tx->type === 'subscription')
                                        Subscription to
                                        <strong>{{ $tx->provider?->name ?? 'Provider' }}</strong>
                                    @elseif($tx->type === 'withdrawal')
                                        Withdrawal
                                    @else
                                        {{ ucfirst($tx->type) }}
                                    @endif
                                </td>

                                {{-- Type Badge --}}
                                <td>
                                    @if($tx->type === 'topup')
                                        <span class="badge bg-success">Top-Up</span>
                                    @elseif($tx->type === 'subscription')
                                        <span class="badge bg-primary">Subscription</span>
                                    @elseif($tx->type === 'withdrawal')
                                        <span class="badge bg-warning text-dark">Withdrawal</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($tx->type) }}</span>
                                    @endif
                                </td>

                                {{-- Amount --}}
                                <td class="text-end fw-bold
                                    @if($tx->type === 'topup') text-success
                                    @else text-danger
                                    @endif
                                ">
                                    @if($tx->type === 'topup')
                                        +{{ number_format($tx->amount, 2) }}
                                    @else
                                        -{{ number_format($tx->amount, 2) }}
                                    @endif
                                    credits
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
