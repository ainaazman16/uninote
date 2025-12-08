<?php

namespace App\Http\Controllers;

use App\Models\ProviderApplication;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProviderApprovalController extends Controller
{
    public function index()
    {
        $applications = ProviderApplication::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.providers.index', compact('applications'));
    }

    public function approve($id)
    {
        $application = ProviderApplication::findOrFail($id);

        // Update application status
        $application->status = 'approved';
        $application->save();

        // Update user role
        $user = $application->user;
        $user->role = 'provider';
        $user->save();

        return redirect()->back()->with('success', 'Application approved successfully.');
    }

    public function reject($id)
    {
        $application = ProviderApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return redirect()->back()->with('error', 'Application rejected.');
    }
}
