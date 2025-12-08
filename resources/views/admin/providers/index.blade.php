@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Provider Applications</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->user->name }}</td>
                        <td>{{ $app->user->email }}</td>
                        <td>{{ $app->reason }}</td>
                        <td>
                            <span class="badge 
                                @if($app->status=='pending') bg-warning 
                                @elseif($app->status=='approved') bg-success 
                                @else bg-danger @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td>{{ $app->created_at->format('d M Y, H:i') }}</td>

                        <td>
                            @if($app->status == 'pending')
                                <form action="{{ route('admin.provider.approve', $app->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Approve</button>
                                </form>

                                <form action="{{ route('admin.provider.reject', $app->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            @else
                                <span class="text-muted">No action</span>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            No provider applications available.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
