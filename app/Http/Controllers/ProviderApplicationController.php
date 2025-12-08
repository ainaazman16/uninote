<?php

namespace App\Http\Controllers;

use App\Models\ProviderApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderApplicationController extends Controller
{
    public function showForm()
    {
        $application = ProviderApplication::where('user_id', Auth::id())->first();

        return view('provider.apply', compact('application'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        ProviderApplication::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'reason' => $request->reason,
                'status' => 'pending',
            ]
            );

            return redirect()->back()->with('success', 'Your application has been submitted successfully.');
    }
        
}
