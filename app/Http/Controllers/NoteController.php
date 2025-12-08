<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function create()
    {
        // Show form to create a new note
        $subjects = \App\Models\Subject::all();
         return view('provider.notes.create', compact('subjects'));
         
    }

    public function store(Request $request)
    {
        // Handle storing the new note
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|mimes:pdf,doc,docx|max:20480', // max 20MB
            'description' => 'nullable|string',
            'is_premium' => 'boolean',
        ]);

        //upload file to storage
        $filePath = $request_>file('file')->store('notes', 'public');

        \app\Models\Note::create([
            'provider_id' => auth()->user()->provider->id,
            'subject_id' => $request->input('subject_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $filePath,
            'is_premium' => $request-> $request->is_premium ?? false,
            'status' => 'pending',
        ]);

        return redirect()->route('notes.create')->with('success','Note uploaded and sent for approval.');
    }
}
