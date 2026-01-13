@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Subjects Management</h2>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                + Add New Subject
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Subject Name</th>
                            <th>Description</th>
                            <th>Notes Count</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
                                <td class="fw-bold">{{ $subject->name }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($subject->description, 50) ?? 'No description' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $subject->notes_count }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $subject->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}"
                                        style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No subjects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $subjects->links() }}
        </div>
    </div>
@endsection
