<?php

namespace App\Http\Controllers;

use App\Models\Note;

class AdminNoteController extends Controller
{
    public function index()
    {
        $notes = Note::with(['provider.user', 'quiz.questions'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.notes.index', compact('notes'));
    }

    public function approve(Note $note)
    {
        $note->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Note approved!');
    }

    public function reject(Note $note)
    {
        $validated = request()->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $note->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Note rejected!');
    }
}

