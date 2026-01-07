@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Dashboard
        </a>
    </div>
    <div class="container mt-4">

        <h2 class="fw-bold mb-4">Notes Approval</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Provider</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Premium</th>
                        <th>Quiz</th>
                        <th>Uploaded At</th>
                        <th>Rejection Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($notes as $note)
                        <tr>
                            <td>{{ $note->title }}</td>
                            <td>{{ $note->provider?->user?->name ?? '-' }}</td>
                            <td>{{ $note->description }}</td>

                            <td>
                                <a href="{{ asset('storage/' . $note->file_path) }}" target="_blank">
                                    View File
                                </a>
                            </td>

                            <td>
                                <span
                                    class="badge 
                                @if ($note->status == 'pending') bg-warning 
                                @elseif($note->status == 'approved') bg-success 
                                @else bg-danger @endif">
                                    {{ ucfirst($note->status) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $note->is_premium ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $note->is_premium ? 'Premium' : 'Free' }}
                                </span>
                            </td>

                            <td>
                                @if ($note->quiz)
                                    <span class="badge bg-info text-dark">Yes</span>
                                    <small class="text-muted ms-1">({{ $note->quiz->questions->count() }}
                                        questions)</small>
                                @else
                                    <span class="badge bg-light text-dark">No</span>
                                @endif
                            </td>

                            <td>{{ $note->created_at->format('d M Y, H:i') }}</td>

                            <td>
                                @if ($note->status == 'rejected' && $note->rejection_reason)
                                    <span class="text-danger">{{ $note->rejection_reason }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if ($note->status == 'pending')
                                    <form action="{{ route('admin.notes.approve', $note->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    </form>

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $note->id }}">
                                        Reject
                                    </button>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $note->id }}" tabindex="-1"
                                        aria-labelledby="rejectModalLabel{{ $note->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.notes.reject', $note->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectModalLabel{{ $note->id }}">
                                                            Reject
                                                            Note</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-3"><strong>Note Title:</strong> {{ $note->title }}
                                                        </p>
                                                        <div class="mb-3">
                                                            <label for="rejection_reason{{ $note->id }}"
                                                                class="form-label">Rejection Reason <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="rejection_reason{{ $note->id }}" name="rejection_reason" rows="4"
                                                                placeholder="Please provide a reason for rejecting this note..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Note</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">No action</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
@endsection
