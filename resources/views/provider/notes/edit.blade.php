@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">Edit Note</h4>

    <form method="POST"
          action="{{ route('provider.notes.update', $note->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $note->title) }}"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-select" required>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}"
                        @selected($note->subject_id == $subject->id)>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox"
                   name="is_premium"
                   value="1"
                   class="form-check-input"
                   @checked($note->is_premium)>
            <label class="form-check-label">
                Premium Note
            </label>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Replace File (optional)
            </label>
            <input type="file"
                   name="file"
                   class="form-control">
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">
                Update Note
            </button>

            <a href="{{ route('provider.notes.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>

</div>
@endsection
