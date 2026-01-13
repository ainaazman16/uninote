@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary mb-3">
            ← Back to Subjects
        </a>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Create New Subject</h4>

                        <form method="POST" action="{{ route('admin.subjects.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Subject Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Optional</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Create Subject
                                </button>
                                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
