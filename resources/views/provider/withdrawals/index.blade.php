@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('provider.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
                ← Back to Dashboard
            </a>
        </div>

        <h2 class="fw-bold mb-4">Withdrawal History</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Bank Details</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th>Payment Proof</th>
                                <th>Rejection Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <strong>RM {{ number_format($withdrawal->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><strong>{{ $withdrawal->bank_name }}</strong></div>
                                            <div>{{ $withdrawal->account_number }}</div>
                                            <div class="text-muted">{{ $withdrawal->account_name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $withdrawal->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if ($withdrawal->payment_proof)
                                            <a href="{{ asset('storage/' . $withdrawal->payment_proof) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-check"></i> View Proof
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($withdrawal->rejection_reason)
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#reasonModal{{ $withdrawal->id }}">
                                                <i class="bi bi-info-circle"></i> View Reason
                                            </button>

                                            <!-- Rejection Reason Modal -->
                                            <div class="modal fade" id="reasonModal{{ $withdrawal->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">Rejection Reason</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="alert alert-danger mb-0">
                                                                <strong>Amount:</strong> RM
                                                                {{ number_format($withdrawal->amount, 2) }}<br>
                                                                <strong>Requested:</strong>
                                                                {{ $withdrawal->created_at->format('d M Y, h:i A') }}<br>
                                                                <hr>
                                                                <strong>Reason:</strong><br>
                                                                {{ $withdrawal->rejection_reason }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No withdrawal requests yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Summary Stats --}}
        @if ($withdrawals->count() > 0)
            <div class="row g-3 mt-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Total Approved</h6>
                            <h3 class="fw-bold text-success mb-0">
                                RM {{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Pending</h6>
                            <h3 class="fw-bold text-warning mb-0">
                                RM {{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Total Rejected</h6>
                            <h3 class="fw-bold text-danger mb-0">
                                RM {{ number_format($withdrawals->where('status', 'rejected')->sum('amount'), 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
