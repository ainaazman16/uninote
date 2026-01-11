@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <h2 class="fw-bold mb-4">Provider Dashboard</h2>
        {{-- Role Indicator --}}
        <div class="alert alert-primary d-flex align-items-center justify-content-between rounded-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-badge fs-4"></i>
                <div>
                    <div class="fw-semibold">
                        Viewing as: <span class="badge bg-primary">Provider</span>
                    </div>
                    <small class="text-muted">
                        You still have full student access (browse, rate, subscribe).
                    </small>
                </div>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-repeat me-1"></i>
                Switch to Student View
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3">

            {{-- Quick Actions --}}
            <div class="mt-4">
                <h4 class="fw-bold mb-3">Quick Actions</h4>

                <div class="d-flex gap-3 flex-wrap">

                    <a href="{{ route('provider.notes.create') }}" class="btn btn-primary">
                        + Upload New Note
                    </a>

                    <a href="{{ route('provider.notes.index') }}" class="btn btn-outline-secondary">
                        View All Notes
                    </a>

                    <a href="{{ route('provider.analytics') }}" class="btn btn-outline-primary">
                        View Analytics
                    </a>

                    <a href="{{ route('provider.withdrawals.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-cash-stack"></i> Withdrawal History
                    </a>

                    <a href="{{ route('provider.chat') }}" class="btn btn-outline-info">
                        <i class="bi bi-chat-dots"></i> Contact Admin Support
                    </a>


                </div>
            </div>
            {{-- Wallet Balance --}}
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

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('provider.withdraw') }}">
                        @csrf

                        <div class="row g-3">

                            {{-- Amount --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Withdrawal Amount</label>
                                <input type="number" name="amount" class="form-control" min="1" step="0.01"
                                    required>
                            </div>

                            {{-- Bank Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" placeholder="e.g. Maybank"
                                    required>
                            </div>

                            {{-- Account Number --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Account Number</label>
                                <input type="text" name="account_number" class="form-control" required>
                            </div>

                            {{-- Account Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Account Holder Name</label>
                                <input type="text" name="account_name" class="form-control" required>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12">
                                <button class="btn btn-dark w-100 mt-2">
                                    Request Withdrawal
                                </button>
                            </div>

                        </div>
                    </form>

                    <small class="text-muted d-block mt-2">
                        Withdrawals require admin approval and manual bank transfer.
                    </small>


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
        <h5 class="fw-bold mb-3">Your Notes Performance</h5>

        <div class="row g-3">
            @forelse($notes as $note)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">

                            <h6 class="fw-bold mb-1">
                                {{ $note->title }}
                            </h6>

                            <p class="text-muted small mb-2">
                                {{ $note->subject->name ?? 'No subject' }}
                            </p>

                            {{-- Rating --}}
                            <div class="mb-1">
                                @php
                                    $avg = round($note->ratings_avg_rating ?? 0);
                                @endphp

                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $avg ? 'text-warning' : 'text-muted' }}">
                                        ★
                                    </span>
                                @endfor

                                <small class="text-muted">
                                    ({{ $note->ratings_count }} reviews)
                                </small>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No notes uploaded yet.</p>
            @endforelse
        </div>

        {{-- Recent Earnings --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Recent Subscriptions</h5>

                        @if ($recentTransactions->count())
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
                                        @foreach ($recentTransactions as $tx)
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

    </div>
@endsection
