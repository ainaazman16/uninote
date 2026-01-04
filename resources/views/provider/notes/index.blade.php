@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="fw-bold mb-4">My Notes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($notes->count() == 0)
        <div class="alert alert-info">You have not uploaded any notes yet.</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Premium?</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Quiz</th>
                    <th>Uploaded At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($notes as $note)
                    <tr>
                        <td>{{ $note->title }}</td>
                        <td>{{ $note->subject->name }}</td>
                        <td>{{ $note->is_premium ? 'Yes' : 'No' }}</td>
                        <td>
                            <span class="badge 
                                @if($note->status=='pending') bg-warning
                                @elseif($note->status=='approved') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($note->status) }}
                            </span>
                        </td>
                        <td><a href="{{ asset('storage/' . $note->file_path) }}" target="_blank">View</a></td>
                        <td>
                            @if($note->quiz)
                                <a href="{{ route('provider.quiz.edit', $note->quiz->id) }}" class="btn btn-sm btn-warning">
                                    Manage Quiz
                                </a>
                            @else
                                <a href="{{ route('provider.quiz.create', $note->id) }}" class="btn btn-sm btn-outline-primary">
                                    Add Quiz
                                </a>
                            @endif

                        </td>
                        <td>{{ $note->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('provider.notes.edit', $note->id) }}"
                            class="btn btn-sm btn-outline-warning">
                                Edit
                            </a>
                            <form method="POST"
                                action="{{ route('provider.notes.destroy', $note->id) }}"
                                class="d-inline"
                                onsubmit="return confirm('Delete this note?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection
