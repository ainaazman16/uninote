@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <h2 class="fw-bold mb-4">Provider Dashboard</h2>

        {{-- Summary Cards --}}
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Wallet Balance</h5>
                        <h2 class="fw-bold">${{ number_format($walletBalance, 2) }}</h2>
                    </div>
                </div>
            </div>
            {{-- Withdrawal Request --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Request Withdrawal</h5>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('provider.withdraw') }}">
                        @csrf

                        <div class="input-group">
                            <input type="number"
                                name="amount"
                                class="form-control"
                                placeholder="Enter amount"
                                min="1"
                                step="0.01"
                                required>

                            <button class="btn btn-dark">Request</button>
                        </div>
                    </form>

                    <small class="text-muted">
                        Withdrawals require admin approval.
                    </small>
                </div>
            </div>
        

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Total Notes</h5>
                        <h2 class="fw-bold">{{ $totalNotes }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Approved</h5>
                        <h2 class="text-success fw-bold">{{ $approvedNotes }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Pending</h5>
                        <h2 class="text-warning fw-bold">{{ $pendingNotes }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Rejected</h5>
                        <h2 class="text-danger fw-bold">{{ $rejectedNotes }}</h2>
                    </div>
                </div>
            </div>

        </div>
        {{-- Earnings Overview --}}
        <div class="row g-3 mt-4">

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Total Earnings</h5>
                        <h2 class="fw-bold text-success">
                            {{ number_format($totalEarnings, 2) }} credits
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">This Month</h5>
                        <h2 class="fw-bold text-primary">
                            {{ number_format($monthlyEarnings, 2) }} credits
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        {{-- Total Downloads --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-secondary">Total Downloads</h5>
                        <h2 class="fw-bold">{{ $totalDownloads }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Earnings --}}
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Recent Subscriptions</h5>

                @if($recentTransactions->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $tx)
                                    <tr>
                                        <td>{{ $tx->created_at->format('d M Y') }}</td>
                                        <td>{{ $tx->student?->name ?? 'Unknown' }}</td>
                                        <td class="fw-bold text-success">
                                            +{{ $tx->amount }} credits
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No earnings yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>


        {{-- Quick Actions --}}
        <div class="mt-4">
            <h4 class="fw-bold mb-3">Quick Actions</h4>

            <div class="d-flex gap-3">

                <a href="{{ route('provider.notes.create') }}" class="btn btn-primary">
                    + Upload New Note
                </a>

                <a href="{{ route('provider.notes.index') }}" class="btn btn-outline-secondary">
                    View All Notes
                </a>

            </div>
        </div>

    </div>
@endsection
