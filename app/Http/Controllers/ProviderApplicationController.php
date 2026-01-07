<?php

namespace App\Http\Controllers;

use App\Models\ProviderApplication;
use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;


class ProviderApplicationController extends Controller
{
    public function showForm()
{
    $user = auth()->user();

    // Check profile completeness
    if (
        !$user->university ||
        !$user->programme ||
        !$user->year_of_study ||
        !$user->profile_photo
    ) {
        return view('provider.incomplete-profile');
    }

    // Get the latest application
    $application = ProviderApplication::where('user_id', $user->id)
        ->latest()
        ->first();

    // Get all applications for history
    $allApplications = ProviderApplication::where('user_id', $user->id)
        ->latest()
        ->get();

    return view('provider.apply', compact('application', 'allApplications'));
}


    public function submit(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'academic_strength' => 'required|string',
            'notes_plan' => 'required|string|in:weekly_lecture_notes,tutorial_solutions,exam_revision_summaries,past_questions_with_answers,comprehensive_study_guide,mixed_content_plan',
        ]);

        ProviderApplication::create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'academic_strength' => $request->academic_strength,
            'notes_plan' => $request->notes_plan,
            'status' => 'pending',
        ]);

            return redirect()->back()->with('success', 'Your application has been submitted successfully.');
    }

    public function approve($id)
{
    $provider = Provider::findOrFail($id);

    $provider->update([
        'status' => 'approved',
    ]);

    // 🔐 Ensure provider has wallet
    Wallet::firstOrCreate(
        ['user_id' => $provider->user_id],
        ['balance' => 0]
    );

    return back()->with('success', 'Provider approved successfully.');
}
        
}
