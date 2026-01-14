<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserSuspendedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AdminUserController extends Controller
 
{
    public function index()
{
    $users = User::where('role', '!=', 'admin')
        ->latest()
        ->paginate(10);

    return view('admin.users.index', compact('users'));
}
public function toggleStatus(User $user)
{
    if ($user->role === 'admin') {
        return back()->with('error', 'Admin account cannot be modified.');
    }

    $user->status = $user->status === 'active'
        ? 'suspended'
        : 'active';

    $user->save();

    return back()->with('success', 'User status updated.');
}
    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'suspend_reason' => 'required|string|max:1000',
        ]);
        $user->update([
            'status' => 'suspended',
            'suspend_reason' => $request->suspend_reason,
        ]);

        Mail::to($user->email)->send(new UserSuspendedMail($user));

        return back()->with('success', 'User has been suspended.');
    }

public function unsuspend(User $user)
{
    $user->update([
        'status' => 'active'
    ]);

    return back()->with('success', 'User has been reactivated.');
}
public function show(User $user)
{
    return view('profile.show', compact('user'));
}
 public function universities()
    {
        $universities = User::whereNotNull('university')
            ->select('university')
            ->groupBy('university')
            ->pluck('university');

        $universityCounts = User::whereNotNull('university')
            ->selectRaw('university, COUNT(*) as count')
            ->groupBy('university')
            ->pluck('count', 'university');

        return view('admin.universities', compact('universities', 'universityCounts'));
    }
}