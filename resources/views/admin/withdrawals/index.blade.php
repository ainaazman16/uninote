@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Dashboard
        </a>
    </div>
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Admin Guidelines
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Withdrawal Approval Guidelines</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>As an admin, you play a crucial role in managing withdrawal requests from providers on our platform. Here are some guidelines to help you in your review and approval process:</p>
        <ul></ul>
            <li><strong>Verification of Details:</strong> Ensure that the bank details provided by the provider are accurate and match the information on file.</li>
            <li><strong>Available Balance Check:</strong> Confirm that the provider has sufficient funds in their wallet to cover the withdrawal amount requested.</li>
            <li><strong>Payment Proof:</strong> When approving a withdrawal, upload a valid payment proof (receipt, transaction ID, etc.) to maintain transparency and accountability.</li>
            <li><strong>Timely Processing:</strong> Aim to process withdrawal requests promptly to ensure providers receive their funds without unnecessary delays.</li>
            <li><strong>Rejection Protocol:</strong> If rejecting a withdrawal request, provide a clear and constructive reason for the rejection to help providers understand the issue.</li>
            <li><strong>Confidentiality:</strong> Maintain the confidentiality of financial information and do not share it outside the platform.</li>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <div class="container mt-4">

        <h2 class="fw-bold mb-4">Withdrawal Requests</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Amount</th>
                            <th>Bank</th>
                            <th>Account Number</th>
                            <th>Account Name</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Action</th>
                            <th>Proof</th>
                            <th>Rejection Reason</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->provider->name }}</td>
                                <td>RM {{ number_format($withdrawal->amount, 2) }}</td>
                                <td>{{ $withdrawal->bank_name }}</td>
                                <td>{{ $withdrawal->account_number }}</td>
                                <td>{{ $withdrawal->account_name }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td>{{ $withdrawal->created_at->format('d M Y') }}</td>
                                <td>
                                    @if ($withdrawal->status === 'pending')
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}"
                                            enctype="multipart/form-data" class="d-inline">
                                            @csrf

                                            <input type="file" name="payment_proof"
                                                class="form-control form-control-sm mb-1" accept="image/*,application/pdf"
                                                required>

                                            <button class="btn btn-sm btn-success w-100">
                                                Approve & Upload Proof
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-danger w-100 mt-1" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                            Reject
                                        </button>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Withdrawal</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST"
                                                        action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="reason{{ $withdrawal->id }}"
                                                                    class="form-label">Rejection Reason</label>
                                                                <textarea name="rejection_reason" id="reason{{ $withdrawal->id }}"
                                                                    class="form-control @error('rejection_reason') is-invalid @enderror" rows="3"
                                                                    placeholder="Enter the reason for rejection (minimum 10 characters)..." required></textarea>
                                                                @error('rejection_reason')
                                                                    <div class="invalid-feedback d-block">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Confirm
                                                                Rejection</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        —
                                    @endif

                                </td>
                                <td>
                                    @if ($withdrawal->payment_proof)
                                        <a href="{{ asset('storage/' . $withdrawal->payment_proof) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            View Proof
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if ($withdrawal->rejection_reason)
                                        <small class="text-muted d-block">{{ $withdrawal->rejection_reason }}</small>
                                    @else
                                        —
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    No withdrawal requests.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
@endsection
