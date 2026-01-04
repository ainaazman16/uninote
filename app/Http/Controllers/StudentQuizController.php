<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;


class StudentQuizController extends Controller
{
    /**
     * Show quiz attempt page
     */
    public function show(Note $note)
    {
        // Ensure note has quiz
        $quiz = $note->quiz;

        if (!$quiz) {
            abort(404, 'Quiz not found for this note.');
        }

        // Optional: ensure student is subscribed if note is premium
        // (reuse your existing subscription logic)

        $questions = $quiz->questions()->with('options')->get();



        return view('student.quiz.show', compact(
            'note',
            'quiz',
            'questions'
        ));
    }
     public function attempt(Note $note)
    {
        // Get quiz from note
        $quiz = $note->quiz;

        if (!$quiz) {
            abort(404, 'Quiz not found for this note.');
        }

        $quiz->load('questions.options');
        

        return view('student.quizzes.attempt', compact('quiz', 'note'));
    }
    /**
     * Handle quiz submission & show result
     */
   public function submit(Request $request, Note $note)
{
    $student = auth()->user();

    $quiz = $note->quiz;

    if (!$quiz) {
        abort(404, 'Quiz not found for this note.');
    }

    $request->validate([
        'answers' => 'required|array'
    ]);

    // Create NEW attempt every time
    $attempt = QuizAttempt::create([
        'quiz_id'    => $quiz->id,
        'student_id' => $student->id,
        'score'      => 0,
        'total_questions'      => $quiz->questions->count(),
    ]);

    $score = 0;

    foreach ($quiz->questions as $question) {
        $selected = $request->answers[$question->id] ?? null;

        // Check if the selected option is correct
        $selectedOption = $question->options->firstWhere('id', $selected);
        $isCorrect = $selectedOption && $selectedOption->is_correct;

        if ($isCorrect) {
            $score++;
        }

        QuizAnswer::create([
            'quiz_attempt_id' => $attempt->id,
            'quiz_question_id'     => $question->id,
            'selected_answer' => $selected,
            'is_correct'      => $isCorrect,
        ]);
    }

    $attempt->update(['score' => $score]);

    return redirect()
        ->route('student.quiz.result', $attempt->id)
        ->with('success', 'Quiz submitted successfully!');
}

     public function result(QuizAttempt $attempt)
    {
        // Security check
        if ($attempt->student_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load([
            'quiz.note',
            'quiz.questions.options',
            'answers'
        ]);
        // Fetch all attempts for THIS quiz by THIS student
    $attempts = QuizAttempt::where('quiz_id', $attempt->quiz_id)
        ->where('student_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();


        return view('student.quizzes.result', compact('attempt', 'attempts'));
    }
}
