@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('student.wallet.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Wallet
        </a>
    </div>
    <div class="container py-4">

        <h3 class="fw-bold mb-4">Top Up Wallet</h3>

        {{-- Info Alert --}}
        <div class="alert alert-info">
            <strong>Important:</strong><br>
            Please transfer the amount to the provided account and upload your payment proof.
            Your wallet balance will be updated after admin approval.
        </div>

        {{-- Top Up Form --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">

                <form method="POST" action="{{ route('student.wallet.topup.process') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Amount --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Top-Up Amount (RM)</label>
                        <input type="number" name="amount" class="form-control" min="10" step="0.01"
                            placeholder="e.g. 50" required>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="transfer" selected>Bank Transfer</option>
                        </select>
                    </div>

                    {{-- Admin Payment Info --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Details</label>
                        <div class="border rounded p-3 bg-light small">
                            <strong>Bank:</strong> MAYBANK<br>
                            <strong>Account Name:</strong> UniNote Admin<br>
                            <strong>Account Number:</strong> 1550 8733 4366<br>
                            <strong>Reference:</strong> Your Name / Email
                        </div>
                    </div>

                    {{-- Upload Proof --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Upload Payment Proof
                        </label>
                        <input type="file" name="proof" class="form-control" accept="image/*,.pdf" required>
                        <small class="text-muted">
                            Accepted: JPG, PNG, PDF
                        </small>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            Submit Top-Up Request
                        </button>

                        <a href="{{ route('student.wallet.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
