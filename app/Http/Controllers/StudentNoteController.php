<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Subscription;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // If note is premium, check subscription
     if ($note->is_premium) {
        $subscription = Subscription::where('student_id', auth()->id())
            ->where('provider_id', $note->provider_id)
            ->where('status', 'active')
            ->first();

        // // Auto-expire
        // if ($subscription && $subscription->expires_at->isPast()) {
        //     $subscription->update(['status' => 'expired']);
        // }

        if (!$subscription) {
            return redirect()
                ->back()
                ->with('error', 'You must subscribe to access this premium note.');
        }
    }

        return view('student.notes.show', compact('note'));
    }

    //Download note file
    public function download(Note $note)
    {
        if ($note->status !== 'approved'){
            abort (403);
        }

        if ($note->is_premium) {

        $subscription = Subscription::where('student_id', Auth::id())
            ->where('provider_id', $note->provider_id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            abort(403);
        }
    }

        return response()->download(
        storage_path('app/public/' . $note->file_path)
    );
    }
}
