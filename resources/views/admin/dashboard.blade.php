@extends('layouts.app')

@section('content')
    <style>
        .admin-card {
            border: none;
            border-radius: 15px;
            padding: 25px;
            transition: all .2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .admin-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        .admin-icon {
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 15px;
        }
    </style>


    <div class="container mt-4">
        @php
            $count = auth()->user()->unreadNotifications->count();
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Admin Dashboard</h2>
            <a href="{{ route('admin.notifications') }}" class="nav-link position-relative">
                🔔
                @if ($count)
                    <span
                        class="badge bg-danger position-absolute top-0 start-100 translate-middle">{{ $count }}</span>
                @endif
            </a>
        </div>

        <div class="row g-4">

            <!-- Provider Applications -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">📝</div>
                    <h5 class="fw-bold">Provider Applications</h5>
                    <p class="text-muted">Review and approve provider requests.</p>
                    <a href="{{ route('admin.provider.applications') }}" class="btn btn-primary w-100">
                        Manage Applications
                    </a>
                </div>
            </div>

            <!-- Notes Approval -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">📚</div>
                    <h5 class="fw-bold">Review Notes</h5>
                    <p class="text-muted">Approve or reject notes submitted by providers.</p>
                    <a href="{{ route('admin.notes.index') }}" class="btn btn-primary w-100">
                        Review Notes
                    </a>
                </div>
            </div>

            <!-- Subjects -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">📘</div>
                    <h5 class="fw-bold">Subjects Management</h5>
                    <p class="text-muted">Manage subjects available in the system.</p>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-primary w-100">
                        Manage Subjects
                    </a>
                </div>
            </div>

            <!-- Withdrawal Requests -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">💰</div>
                    <h5 class="fw-bold">
                        Withdrawal Requests
                        @if ($pendingWithdrawals > 0)
                            <span class="badge bg-danger ms-2">
                                {{ $pendingWithdrawals }}
                            </span>
                        @endif
                    </h5>

                    <p class="text-muted">Approve or reject provider withdrawal requests.</p>

                    <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-warning w-100">
                        Manage Withdrawals
                    </a>
                </div>
            </div>

            <!-- Users Management -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">👥</div>
                    <h5 class="fw-bold">User Management</h5>
                    <p class="text-muted">View and manage student and provider accounts.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary w-100">
                        Manage Users
                    </a>
                </div>
            </div>

            <!-- Ratings & Feedback Moderation -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">⭐</div>
                    <h5 class="fw-bold">Ratings & Feedback</h5>
                    <p class="text-muted">Moderate user ratings and feedback.</p>
                    <a href="{{ route('admin.ratings.index') }}" class="btn btn-primary w-100">
                        Manage Ratings
                    </a>
                </div>
            </div>

            <!-- Wallet Top-Up Approvals -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">💳</div>
                    <h5 class="fw-bold">
                        Wallet Top-Ups
                        @if ($pendingTopups > 0)
                            <span class="badge bg-danger ms-2">
                                {{ $pendingTopups }}
                            </span>
                        @endif
                    </h5>
                    <p class="text-muted">
                        Review student wallet top-up requests.
                    </p>
                    <a href="{{ url('/wallet-topups') }}" class="btn btn-success w-100">
                        Approve Top-Ups
                    </a>
                </div>
            </div>

            {{-- <!-- Analytics -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">📊</div>
                    <h5 class="fw-bold">Analytics & Reports</h5>
                    <p class="text-muted">View system activity and provider statistics.</p>
                    <a href="{{ route('admin.analytics.index') }}" class="btn btn-primary w-100">Open Analytics</a>
                </div>
            </div> --}}

            <!-- Provider Support Chats -->
            <div class="col-md-4">
                <div class="card admin-card text-center">
                    <div class="admin-icon">💬</div>
                    <h5 class="fw-bold">
                        Provider Support Chats
                        @if ($pendingChats > 0)
                            <span class="badge bg-danger ms-2">
                                {{ $pendingChats }}
                            </span>
                        @endif
                    </h5>
                    <p class="text-muted">Respond to provider support inquiries.</p>
                    <a href="{{ route('admin.chats') }}" class="btn btn-primary w-100">
                        View Chats
                    </a>
                </div>
            </div>

        </div>

        <!-- Analytics Cards & Charts Section -->
        <div class="row g-4 mt-4">
            @include('admin.analytics.cards')
            @include('admin.analytics.charts')
        </div>

    </div>


    @include('admin.analytics.scripts')
@endsection
