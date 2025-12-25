<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Subject;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('provider_id', Auth::user()->provider->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

         $subscription = Subscription::where('student_id', auth()->id())
        ->where('provider_id', Auth::user()->provider->id)
        ->latest()
        ->first();

    if (!$subscription || !$subscription->isActive()) {
        return redirect()
            ->route('student.dashboard')
            ->with('error', 'Your subscription has expired.');
    }

        return view('provider.notes.index', compact('notes'));
    }

    public function create()
    {
        // Show form to create a new note
        $subjects = Subject::all();
         return view('provider.notes.create', compact('subjects'));
         
    }

    public function store(Request $request)
{
    $request->validate([
        'title'       => 'required|max:255',
        'description' => 'nullable|string',
        'subject_id'  => 'required|exists:subjects,id',
        'file'        => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        'is_premium'  => 'nullable|boolean'
    ]);

    // Upload file
    $path = $request->file('file')->store('notes', 'public');

    Note::create([
        'provider_id'=> Auth::user()->provider->id,
        'subject_id'  => $request->subject_id,
        'title'       => $request->title,
        'description' => $request->description,
        'file_path'   => $path,
        'is_premium'  => $request->is_premium ? 1 : 0,
        'status'      => 'pending',
    ]);

    return redirect()->route('provider.notes.index')
                     ->with('success', 'Note uploaded! Waiting for admin approval.');
}
}
