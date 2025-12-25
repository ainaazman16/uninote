<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentNoteController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::all();

        $notes = Note::with(['provider.user', 'subject'])
            ->where('status', 'approved')
            ->when($request->subject_id, function ($query) use ($request) {
                $query->where('subject_id', $request->subject_id);
            })
            ->latest()
            ->get();

            return view('student.notes.index' , compact('notes', 'subjects')); 
    }

    //View note details
    public function show(Note $note)
    {
        if ($note->status !== 'approved') {
            abort(403);
        }

        return view('student.notes.show', compact('note'));
    }

    //Download note file
    public function download(Note $note)
    {
        if ($note->status !== 'approved'){
            abort (403);
        }

        //Premium logic
        if ($note->is_premium){
            return back()->with('error', 'This is a premium note. Please subscribe to download.');
        }

        return Storage::disk('public')->download($note->file_path);
    }
}
