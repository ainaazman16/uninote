@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="fw-bold mb-4">Notes Approval</h2>

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
                    <th>Title</th>
                    <th>Provider</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Uploaded At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($notes as $note)
                    <tr>
                        <td>{{ $note->title }}</td>
                        <td>{{ $note->provider->name }}</td>
                        <td>{{ $note->description }}</td>

                        <td>
                            <a href="{{ asset('storage/' . $note->file_path) }}" target="_blank">
                                View File
                            </a>
                        </td>

                        <td>
                            <span class="badge 
                                @if($note->status=='pending') bg-warning 
                                @elseif($note->status=='approved') bg-success 
                                @else bg-danger @endif">
                                {{ ucfirst($note->status) }}
                            </span>
                        </td>

                        <td>{{ $note->created_at->format('d M Y, H:i') }}</td>

                        <td>
                            @if($note->status == 'pending')
                                <form action="{{ route('admin.notes.approve', $note->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Approve</button>
                                </form>

                                <form action="{{ route('admin.notes.reject', $note->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
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
