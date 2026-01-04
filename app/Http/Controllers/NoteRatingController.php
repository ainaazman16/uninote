<?php

namespace App\Http\Controllers;
use App\Models\Note;
use App\Models\NoteRating;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class NoteRatingController extends Controller
{
    public function store(Request $request, Note $note)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    NoteRating::updateOrCreate(
        [
            'note_id' => $note->id,
            'student_id' => auth()->id(),
        ],
        [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]
    );

    return back()->with('success', 'Rating submitted');
}
 public function update(Request $request, NoteRating $rating)
    {
        abort_if($rating->student_id !== Auth::id(), 403);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $rating->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Rating updated.');
    }

    public function destroy(NoteRating $rating)
    {
        abort_if($rating->student_id !== Auth::id(), 403);

        $rating->delete();

        return back()->with('success', 'Rating deleted.');
    }

}
