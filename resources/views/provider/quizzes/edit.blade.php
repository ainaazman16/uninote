@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <h4 class="fw-bold mb-3">
            Edit Quiz for: {{ optional($quiz->note)->title ?? 'Note' }}
        </h4>

        <p class="text-muted mb-4">
            Update questions and mark the correct option for each.
        </p>

        <form method="POST" action="{{ route('provider.quiz.update', $quiz->id) }}">
            @csrf
            @method('PUT')

            <div id="questions-wrapper">
                @foreach ($quiz->questions as $qIndex => $question)
                    <div class="card mb-4 question-item">
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Question</label>
                                <input type="text" name="questions[{{ $qIndex }}][question]" class="form-control"
                                    value="{{ $question->question }}" required>
                            </div>

                            <div class="row g-2 mb-3">
                                @foreach ($question->options as $oIndex => $option)
                                    <div class="col-md-6">
                                        <input type="text"
                                            name="questions[{{ $qIndex }}][options][{{ $oIndex }}]"
                                            class="form-control" value="{{ $option->option_text }}"
                                            placeholder="Option {{ chr(65 + $oIndex) }}" required>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Correct Answer</label>
                                <select name="questions[{{ $qIndex }}][correct]" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($question->options as $oIndex => $option)
                                        <option value="{{ $oIndex }}" {{ $option->is_correct ? 'selected' : '' }}>
                                            {{ chr(65 + $oIndex) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-secondary mb-3" onclick="addQuestion()">
                + Add Another Question
            </button>

            <div class="mt-4">
                <button class="btn btn-primary">Save Changes</button>
                <a href="{{ route('provider.notes.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>

        </form>

    </div>

    <script>
        let questionIndex = {{ $quiz->questions->count() }};

        function addQuestion() {
            const wrapper = document.getElementById('questions-wrapper');
            const q = questionIndex;
            const optionLetters = ['A', 'B', 'C', 'D'];

            const optionsHtml = optionLetters.map((letter, idx) => `
        <div class="col-md-6">
            <input type="text"
                   name="questions[${q}][options][${idx}]"
                   class="form-control"
                   placeholder="Option ${letter}"
                   required>
        </div>
    `).join('');

            const correctOptions = optionLetters.map((letter, idx) => `
        <option value="${idx}">${letter}</option>
    `).join('');

            const html = `
        <div class="card mb-4 question-item">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <input type="text"
                           name="questions[${q}][question]"
                           class="form-control"
                           required>
                </div>
                <div class="row g-2 mb-3">
                    ${optionsHtml}
                </div>
                <div class="mb-2">
                    <label class="form-label">Correct Answer</label>
                    <select name="questions[${q}][correct]" class="form-select" required>
                        <option value="">-- Select --</option>
                        ${correctOptions}
                    </select>
                </div>
            </div>
        </div>
    `;

            wrapper.insertAdjacentHTML('beforeend', html);
            questionIndex++;
        }
    </script>
@endsection
