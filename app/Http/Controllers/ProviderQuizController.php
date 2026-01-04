<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderQuizController extends Controller
{
    /**
     * Show quiz creation form for a note
     */
    public function create(Note $note)
    {
        // Security: ensure provider owns this note
        if ($note->provider_id !== Auth::user()->provider->id) {
            abort(403);
        }

        // Check if quiz already exists
        $quiz = Quiz::where('note_id', $note->id)->first();

        return view('provider.quizzes.create', compact('note', 'quiz'));
    }

    /**
     * Store quiz with questions & options
     */
    public function store(Request $request, $noteId)
    {
        $note = Note::where('id', $noteId)
            ->where('provider_id', Auth::user()->provider->id)
            ->firstOrFail();

        // ✅ Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.correct' => 'required|in:A,B,C,D',
        ]);

        // ✅ Create quiz
        $quiz = Quiz::create([
            'note_id' => $note->id,
            'provider_id' => Auth::user()->provider->id,
        ]);

        // ✅ Store questions
        foreach ($request->questions as $q) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $q['question'],
            ]);

            foreach ($q['options'] as $oIndex => $optionText) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct' => ($oIndex == $q['correct']),
                ]);
            }
        }

        return redirect()
            ->route('provider.notes.index')
            ->with('success', 'Quiz created successfully!');
    }

    /**
     * Edit quiz
     */
    public function edit(Quiz $quiz)
    {
        if ($quiz->provider_id !== Auth::user()->provider->id) {
            abort(403);
        }

        $quiz->load('questions.options');

        return view('provider.quizzes.edit', compact('quiz'));
    }

    /**
     * Update quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->provider_id !== Auth::user()->provider->id) {
            abort(403);
        }

        $request->validate([
            'questions' => 'required|array|min:1',
        ]);

        // Delete old questions & options
        foreach ($quiz->questions as $question) {
            $question->options()->delete();
        }
        $quiz->questions()->delete();

        // Recreate
        foreach ($request->questions as $qData) {

            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $qData['question'],
            ]);

            foreach ($qData['options'] as $oIndex => $optionText) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct' => ($oIndex == $qData['correct']),
                ]);
            }
        }

        return back()->with('success', 'Quiz updated successfully!');
    }
    
}
