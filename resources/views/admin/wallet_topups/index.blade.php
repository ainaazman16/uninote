@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.dashboard') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Dashboard
    </a>
    </div>
<div class="container py-4">

    <h3 class="fw-bold mb-4">Pending Wallet Top-Ups</h3>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($pendingTopups->count())
        <div class="card shadow-sm border-0 rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Proof</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($pendingTopups as $topup)
                            <tr>
                                <td>
                                    {{ $topup->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">
                                        {{ $topup->created_at->format('h:i A') }}
                                    </small>
                                </td>

                                <td>
                                    <strong>{{ $topup->user->name }}</strong><br>
                                    <small class="text-muted">{{ $topup->user->email }}</small>
                                </td>

                                <td class="fw-bold text-success">
                                    RM {{ number_format($topup->amount, 2) }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ strtoupper($topup->method) }}
                                    </span>
                                </td>

                                <td>
                                    @if($topup->proof_path)
                                       <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#proofModal{{ $topup->id }}">
                                        View Proof
                                    </button>

                                    @else
                                        <span class="text-muted">No file</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                </td>

                                <td class="text-center">
                                    <form action="{{ route('admin.wallet.topups.approve', $topup->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Approve this top-up?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.wallet.topups.reject', $topup->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Reject this top-up?')">
                                            Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            {{-- Proof Preview Modal --}}
                            <div class="modal fade" id="proofModal{{ $topup->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Payment Proof – {{ $topup->user->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body text-center">

                                            @php
                                                $ext = pathinfo($topup->proof_path, PATHINFO_EXTENSION);
                                            @endphp

                                            @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
                                                <img src="{{ asset('storage/'.$topup->proof_path) }}"
                                                    class="img-fluid rounded shadow"
                                                    alt="Payment Proof">
                                            @elseif(strtolower($ext) === 'pdf')
                                                <iframe src="{{ asset('storage/'.$topup->proof_path) }}"
                                                        width="100%"
                                                        height="500px"
                                                        class="border rounded">
                                                </iframe>
                                            @else
                                                <p class="text-muted">Unsupported file type</p>
                                            @endif

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No pending top-ups at the moment.
        </div>
    @endif

</div>
@endsection
