@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
    <a href="{{ route('provider.dashboard') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Dashboard
    </a>
    </div>
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
                        <label class="form-label fw-semibold">Subject</label>

                        <select name="subject_id" id="subject-select" class="form-select" required
                            onchange="toggleOtherSubject()">

                            <option value="">-- Select Subject --</option>

                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                    {{ $subject->name }}
                                </option>
                            @endforeach

                            <option value="other" @selected(old('subject_id') === 'other')>Others</option>
                        </select>
                    </div>

                    {{-- Other subject input --}}
                    <div class="mb-3 d-none" id="other-subject-wrapper">
                        <label class="form-label fw-semibold">New Subject</label>
                        <input type="text" name="other_subject" id="other-subject-input" class="form-control"
                            placeholder="Enter new subject">
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Note File *</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="hidden" name="is_premium" value="0">

                        <input class="form-check-input" type="checkbox" name="is_premium" id="premiumCheck" value="1">

                        <label class="form-check-label" for="premiumCheck">
                            Mark as Premium
                        </label>
                    </div>


                    <button class="btn btn-primary w-100">Upload Note</button>

                </form>

            </div>
        </div>

    </div>
    <script>
        function toggleOtherSubject() {
            const select = document.getElementById('subject-select');
            const otherWrapper = document.getElementById('other-subject-wrapper');
            const otherInput = document.getElementById('other-subject-input');

            if (select.value === 'other') {
                otherWrapper.classList.remove('d-none');
                otherInput.setAttribute('required', 'required');
            } else {
                otherWrapper.classList.add('d-none');
                otherInput.removeAttribute('required');
            }
        }

        document.addEventListener('DOMContentLoaded', toggleOtherSubject);
    </script>

@endsection
