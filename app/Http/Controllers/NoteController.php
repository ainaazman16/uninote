<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NoteUpdatedNotification;
use App\Models\Note;
use App\Models\User;
use App\Models\Subject;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('provider_id', Auth::user()->provider->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('provider.notes.index', compact('notes'));

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
    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'description' => 'nullable|string',
        'subject_id' => 'required',
        'other_subject' => 'nullable|string|max:255',
        'file' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        'is_premium' => 'nullable|boolean',
    ]);

    $validator->after(function ($validator) use ($request) {
        if ($request->subject_id === 'other') {
            if (!$request->filled('other_subject')) {
                $validator->errors()->add('other_subject', 'Please enter subject name');
            }

            return;
        }

        if (!Subject::whereKey($request->subject_id)->exists()) {
            $validator->errors()->add('subject_id', 'Selected subject id is invalid.');
        }
    });

    $validator->validate();

    // Upload file
    $path = $request->file('file')->store('notes', 'public');

    // 🧠 If "Others" selected
    if ($request->subject_id === 'other') {
        $subject = Subject::firstOrCreate([
            'name' => $request->other_subject,
        ]);

        $subjectId = $subject->id;
    } else {
        $subjectId = $request->subject_id;
    }

    Note::create([
        'provider_id'=> Auth::user()->provider->id,
        'subject_id'  => $subjectId,
        'title'       => $request->title,
        'description' => $request->description,
        'file_path'   => $path,
        'is_premium'  => $request->is_premium, // 0 or 1
        'status'      => 'pending',
    ]);

    return redirect()->route('provider.notes.index')
                     ->with('success', 'Note uploaded! Waiting for admin approval.');
}

public function edit(Note $note)
{
    $this->authorizeNote($note);

    $subjects = Subject::orderBy('name')->get();

    return view(
        'provider.notes.edit',
        compact('note', 'subjects')
    );
}
public function update(Request $request, Note $note)
{
    $this->authorizeNote($note);

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'subject_id' => 'required|exists:subjects,id',
        'is_premium' => 'boolean',
        'file' => 'nullable|file|mimes:pdf|max:10240',
    ]);

    if ($request->hasFile('file')) {
        $data['file_path'] = $request->file('file')
            ->store('notes', 'public');
    }

    // Reset approval
    $data['status'] = 'pending';

    $note->update($data);
    // Notify all admins
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NoteUpdatedNotification($note));
        }

    return redirect()
        ->route('provider.notes.index')
        ->with('success', 'Note updated and sent for re-approval.');
}

public function destroy(Note $note)
{
    $this->authorizeNote($note);

    $note->delete();

    return back()->with(
        'success',
        'Note deleted successfully.'
    );
}
private function authorizeNote(Note $note)
{
    if ($note->provider_id !== auth()->user()->provider->id) {
        abort(403);
    }
}

}
