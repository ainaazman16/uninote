@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <h4 class="fw-bold mb-3">Quiz Results: {{ $attempt->quiz->note->title ?? 'Quiz' }}</h4>

        <div class="alert alert-info">
            <strong>Your Score:</strong>
            {{ $attempt->score }} / {{ $attempt->total_questions }}
            ({{ round(($attempt->score / $attempt->total_questions) * 100) }}%)
        </div>
        <hr class="my-4">

        <h6 class="fw-bold">Your Attempts</h6>

        @if ($attempts->count())
            <ul class="list-group mt-2">
                @foreach ($attempts as $a)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            {{ $a->created_at->format('d M Y, H:i') }}
                        </span>

                        <span class="fw-bold">
                            {{ $a->score }} / {{ $a->total_questions }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No previous attempts.</p>
        @endif

        <hr>

        @foreach ($attempt->quiz->questions as $index => $question)
            @php
                $userAnswer = $attempt->answers->firstWhere('quiz_question_id', $question->id);
                $selectedOptionId = $userAnswer->selected_answer ?? null;
                $correctOption = $question->options->firstWhere('is_correct', true);
            @endphp

            <div class="card mb-3">
                <div class="card-body">

                    <h6 class="fw-bold">
                        Q{{ $index + 1 }}. {{ $question->question }}
                    </h6>

                    <ul class="list-group list-group-flush mt-2">
                        @foreach ($question->options as $option)
                            <li
                                class="list-group-item
                            @if ($option->is_correct) list-group-item-success
                            @elseif($option->id == $selectedOptionId && !$option->is_correct)
                                list-group-item-danger @endif
                        ">
                                {{ $option->option_text }}

                                @if ($option->is_correct)
                                    <span class="badge bg-success ms-2">✓ Correct Answer</span>
                                @elseif($option->id == $selectedOptionId)
                                    <span class="badge bg-danger ms-2">✗ Your Answer</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        @endforeach
        <div class="text-center mt-4">
            <a href="{{ route('student.quiz.attempt', $attempt->quiz->note) }}" class="btn btn-outline-primary">
                🔁 Reattempt Quiz
            </a>

            <a href="{{ route('student.notes.show', $attempt->quiz->note) }}" class="btn btn-secondary ms-2">
                Back to Note
            </a>
        </div>
    </div>
@endsection
