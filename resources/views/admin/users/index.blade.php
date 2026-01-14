@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Dashboard
        </a>
    </div>

        <h3 class="fw-bold mb-4">User Management</h3>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $user->name }}</td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if ($user->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="text-end">
                                    @if ($user->status === 'active')
                                        <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}"
                                            class="d-inline" onsubmit="return confirm('Suspend this user?')">
                                            @csrf
                                            <div class="mb-2">
                                                <textarea name="suspend_reason" class="form-control form-control-sm" placeholder="Enter suspend reason..." required></textarea>
                                            </div>
                                            <button class="btn btn-danger btn-sm">
                                                Suspend
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.unsuspend', $user->id) }}"
                                            class="d-inline" onsubmit="return confirm('Unsuspend this user?')">
                                            @csrf
                                            <button class="btn btn-success btn-sm">
                                                Unsuspend
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection
