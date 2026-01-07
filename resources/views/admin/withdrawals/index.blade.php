@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.dashboard') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Dashboard
    </a>
    </div>
<div class="container mt-4">

    <h2 class="fw-bold mb-4">Withdrawal Requests</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Provider</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->provider->name }}</td>
                        <td>RM {{ number_format($withdrawal->amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $withdrawal->status === 'approved' ? 'success' :
                                ($withdrawal->status === 'rejected' ? 'danger' : 'warning')
                            }}">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </td>
                        <td>{{ $withdrawal->created_at->format('d M Y') }}</td>
                        <td>
                            @if($withdrawal->status === 'pending')
                                <form method="POST"
                                      action="{{ route('admin.withdrawals.approve', $withdrawal) }}"
                                      class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.withdrawals.reject', $withdrawal) }}"
                                      class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">
                                        Reject
                                    </button>
                                </form>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
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
