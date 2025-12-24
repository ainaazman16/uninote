<?php

namespace App\Http\Controllers;

use App\Models\Note;

class AdminNoteController extends Controller
{
    public function index()
    {
        $notes = Note::orderBy('created_at', 'desc')->get();
        return view('admin.notes.index', compact('notes'));
    }

    public function approve(Note $note)
    {
        $note->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Note approved!');
    }

    public function reject(Note $note)
    {
        $note->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Note rejected!');
    }
}

