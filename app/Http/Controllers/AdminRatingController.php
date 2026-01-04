<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NoteRating;

class AdminRatingController extends Controller
{
    public function index()
    {
        $ratings = NoteRating::with(['student', 'note.provider.user'])
            ->latest()
            ->paginate(20);

        return view('admin.ratings.index', compact('ratings'));
    }

    public function destroy(NoteRating $rating)
    {
        $rating->delete();

        return back()->with('success', 'Feedback removed successfully');
    }
}
