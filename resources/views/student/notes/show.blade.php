@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('student.notes.index') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        ← Back to Browse Notes
    </a>
    </div>
    <div class="container py-4">

        <div class="mb-4">
            <h2 class="fw-bold">{{ $note->title }}</h2>
            <div class="mb-2">
                @if ($ratingCount > 0)
                    <span class="text-warning fw-bold">
                        ★ {{ $averageRating }}
                    </span>
                    <span class="text-muted">
                        ({{ $ratingCount }} reviews)
                    </span>
                @else
                    <span class="text-muted">No ratings yet</span>
                @endif
            </div>


            <p class="text-muted">
                Subject: {{ $note->subject->name ?? 'N/A' }} <br>
                Provider: {{ optional($note->provider?->user)->name ?? 'Unknown' }}
            </p>

            <p>{{ $note->description }}</p>
        </div>

        {{-- NOTE PREVIEW --}}
        @if ($note->is_premium)
            <span class="badge bg-warning text-dark mb-2">🔒 Premium Note</span>
        @endif

        <h3>{{ $note->title }}</h3>

        <p class="text-muted">{{ $note->description }}</p>

        <a href="{{ route('student.notes.download', $note->id) }}" class="btn btn-primary">
            Download Note
        </a>

        @if ($note->quiz)
            <a href="{{ route('student.quiz.attempt', $note->id) }}" class="btn btn-success">
                📝 Attempt Quiz
            </a>
        @endif

        @if (Str::endsWith($note->file_path, '.pdf'))
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Preview</h5>

                    <iframe src="{{ asset('storage/' . $note->file_path) }}#page=1" width="100%" height="600px"
                        style="border:none;">
                    </iframe>

                    <p class="text-muted small mt-2">
                        Preview shows first page only.
                    </p>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Preview not available for this file type.
            </div>
        @endif
        <div>
            @if ($isSubscribed)
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Rate this note</h6>

                        <form method="POST" action="{{ route('notes.rate', $note->id) }}">
                            @csrf

                            <div class="mb-3">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" class="btn-check" name="rating" id="star{{ $i }}"
                                        value="{{ $i }}" required>
                                    <label class="btn btn-outline-warning" for="star{{ $i }}">★</label>
                                @endfor
                            </div>

                            <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Optional feedback..."></textarea>

                            <button class="btn btn-primary btn-sm">
                                Submit Rating
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            @if ($userRating)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Your Rating</h6>

                        <form method="POST" action="{{ route('ratings.update', $userRating->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="me-1">
                                        <input type="radio" name="rating" value="{{ $i }}"
                                            {{ $userRating->rating == $i ? 'checked' : '' }}>
                                        ★
                                    </label>
                                @endfor
                            </div>

                            <textarea name="comment" class="form-control mb-2" rows="2">{{ $userRating->comment }}</textarea>

                            <button class="btn btn-primary btn-sm">
                                Update
                            </button>
                        </form>

                        <form method="POST" action="{{ route('ratings.destroy', $userRating->id) }}" class="mt-2"
                            onsubmit="return confirm('Delete your rating?');">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-outline-danger btn-sm">
                                Delete Rating
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
        {{-- Ratings & Feedback --}}
        <div class="card shadow-sm mt-4">
            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    Ratings & Feedback
                </h6>

                {{-- Average rating --}}
                <div class="mb-3">
                    <span class="fw-bold">
                        ⭐ {{ number_format($averageRating ?? 0, 1) }}
                    </span>
                    <span class="text-muted">
                        ({{ $ratingCount }} reviews)
                    </span>
                </div>

                <hr>

                {{-- Individual feedback --}}
                @forelse($ratings as $rating)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $rating->student->name }}</strong>
                            <span class="text-warning">
                                {{ str_repeat('★', $rating->rating) }}
                            </span>
                        </div>

                        @if ($rating->comment)
                            <p class="mb-1 text-muted">
                                "{{ $rating->comment }}"
                            </p>
                        @endif

                        <small class="text-muted">
                            {{ $rating->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <hr>
                @empty
                    <p class="text-muted mb-0">
                        No feedback yet for this note.
                    </p>
                @endforelse

            </div>
        </div>


    </div>
@endsection
