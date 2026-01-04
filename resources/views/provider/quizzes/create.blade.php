@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3">
        Create Quiz for: {{ $note->title }}
    </h4>

    <p class="text-muted mb-4">
        This quiz is optional. Students will see the result immediately after submission.
    </p>

    <form method="POST" action="{{ route('provider.quiz.store', $note->id) }}">
        @csrf

        {{-- Quiz title --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Quiz Title</label>
            <input type="text" name="title"
                   class="form-control"
                   placeholder="e.g. Chapter 1 Quiz"
                   required>
        </div>

        <hr class="mb-4">

        <h6 class="fw-bold mb-3">Questions</h6>

        <div id="questions-wrapper">

            {{-- Question block --}}
            <div class="card mb-4 question-item">
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text"
                               name="questions[0][question]"
                               class="form-control"
                               required>
                    </div>

                    <div class="row g-2 mb-3">
                        @foreach(['A','B','C','D'] as $option)
                            <div class="col-md-6">
                                <input type="text"
                                       name="questions[0][options][{{ $option }}]"
                                       class="form-control"
                                       placeholder="Option {{ $option }}"
                                       required>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Correct Answer</label>
                        <select name="questions[0][correct]"
                                class="form-select"
                                required>
                            <option value="">-- Select --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                </div>
            </div>

        </div>

        <button type="button"
                class="btn btn-outline-secondary mb-3"
                onclick="addQuestion()">
            + Add Another Question
        </button>

        <div class="mt-4">
            <button class="btn btn-primary">
                Save Quiz
            </button>
            <a href="{{ route('provider.notes.index') }}"
               class="btn btn-secondary ms-2">
                Cancel
            </a>
        </div>

    </form>

</div>

{{-- JS for dynamic questions --}}
<script>
let questionIndex = 1;

function addQuestion() {
    const wrapper = document.getElementById('questions-wrapper');

    let optionsHtml = '';
    ['A','B','C','D'].forEach(option => {
        optionsHtml += `
            <div class="col-md-6">
                <input type="text"
                       name="questions[${questionIndex}][options][${option}]"
                       class="form-control"
                       placeholder="Option ${option}"
                       required>
            </div>
        `;
    });

    const html = `
        <div class="card mb-4 question-item">
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <input type="text"
                           name="questions[${questionIndex}][question]"
                           class="form-control"
                           required>
                </div>

                <div class="row g-2 mb-3">
                    ${optionsHtml}
                </div>

                <div class="mb-2">
                    <label class="form-label">Correct Answer</label>
                    <select name="questions[${questionIndex}][correct]"
                            class="form-select"
                            required>
                        <option value="">-- Select --</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
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
