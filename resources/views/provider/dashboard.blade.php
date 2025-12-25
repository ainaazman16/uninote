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
