@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="fw-bold mb-1">Quiz: {{ $note->title }}</h4>
                <p class="text-muted mb-0">
                    Answer all questions. Results will be shown immediately after submission.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('student.quiz.submit', $note->id) }}">
            @csrf

            @forelse($quiz->questions as $index => $question)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        {{-- Question --}}
                        <h6 class="fw-bold mb-3">
                            Q{{ $index + 1 }}. {{ $question->question }}
                        </h6>

                        {{-- Options --}}
                        @if ($question->options && $question->options->count() > 0)
                            @foreach ($question->options as $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                        value="{{ $option->id }}" id="q{{ $question->id }}_{{ $option->id }}" required>

                                    <label class="form-check-label" for="q{{ $question->id }}_{{ $option->id }}">
                                        {{ $option->option_text }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-danger">No options available for this question.</p>
                        @endif

                    </div>
                </div>
            @empty
                <div class="alert alert-warning">
                    No questions found for this quiz.
                </div>
            @endforelse


            <div class="text-end">
                <button class="btn btn-primary px-4">
                    Submit Quiz
                </button>
            </div>

        </form>

    </div>
@endsection
