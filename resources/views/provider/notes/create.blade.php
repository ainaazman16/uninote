@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="fw-bold mb-4">Upload New Note</h2>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Fix the errors below:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('provider.notes.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (optional)</label>
                    <textarea name="description" rows="3" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject *</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Note File *</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_premium" id="premiumCheck">
                    <label class="form-check-label" for="premiumCheck">
                        Mark as Premium
                    </label>
                </div>

                <button class="btn btn-primary w-100">Upload Note</button>

            </form>

        </div>
    </div>

</div>

@endsection
