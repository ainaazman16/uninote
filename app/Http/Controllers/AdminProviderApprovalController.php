<?php

namespace App\Http\Controllers;

use App\Models\ProviderApplication;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProviderApprovalController extends Controller
{
    public function index()
    {
        $applications = ProviderApplication::with('user')
        ->latest()
        ->get();

    return view('admin.providers.index', compact('applications'));
    }

    public function approve($id)
{
    $application = ProviderApplication::findOrFail($id);
    $user = $application->user;

    // Create provider record if not exists
    $user->provider()->updateOrCreate(
        ['user_id' => $user->id],
        ['status' => 'approved']
    );

    // Update application status
    $application->status = 'approved';
    $application->admin_comment = request()->input('admin_comment');
    $application->save();

    // Change user role
    $user->role = 'provider';
    $user->save();

    return back()->with('success', 'Provider approved successfully!');
}

    public function reject($id)
    {
        $application = ProviderApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->admin_comment = request()->input('admin_comment');
        $application->save();

        return redirect()->back()->with('error', 'Application rejected.');
    }
}
