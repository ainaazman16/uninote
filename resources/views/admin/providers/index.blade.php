@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.dashboard') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Dashboard
    </a>
    </div>
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Provider Applications</h2>
        <span class="text-muted">Review and manage note provider requests</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Applicant</th>
                    <th>Email</th>
                    <th>Motivation</th>
                    <th>Status</th>
                    <th>Applied On</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->user->name ?? 'User Deleted' }}</td>
                        <td>{{ $app->user->email ?? '-' }}</td>
                        <td style="max-width: 300px;">
                            {{ Str::limit($app->reason, 120) }}
                        </td>
                        <td>
                            <span class="badge 
                                @if($app->status=='pending') bg-warning 
                                @elseif($app->status=='approved') bg-success 
                                @else bg-danger @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td>{{ $app->created_at->format('d M Y, H:i') }}</td>

                        <td class="text-center">

    {{-- View Profile --}}
    <a href="{{ route('admin.users.show', $app->user->id) }}"
       class="btn btn-info btn-sm mb-1">
        View Profile
    </a>

    @if($app->status == 'pending')
        <form action="{{ route('admin.provider.approve', $app->id) }}" method="POST">
            @csrf
            <textarea name="admin_comment" class="form-control mb-2"
                placeholder="Optional feedback"></textarea>
            <button class="btn btn-success btn-sm w-100">Approve</button>
        </form>

        <form action="{{ route('admin.provider.reject', $app->id) }}" method="POST" class="mt-2">
            @csrf
            <textarea name="admin_comment" class="form-control mb-2"
                placeholder="Reason for rejection" required></textarea>
            <button class="btn btn-danger btn-sm w-100">Reject</button>
        </form>

    @else
        <span class="text-muted">No action</span>
    @endif

</td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No provider applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
