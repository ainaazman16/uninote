<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // PUBLIC VIEW (student/provider/admin)
    public function show(User $user)
    {
        return view('profile.show', compact('user'));
    }

    // EDIT OWN PROFILE
    public function edit()
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Admins cannot edit their profile.');
        return view('profile.edit');
    }

    // UPDATE OWN PROFILE
    public function update(Request $request)
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Admins cannot edit their profile.');
        $user = Auth::user();

        $data = $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
            'university'    => 'nullable|string|max:255',
            'programme'     => 'nullable|string|max:255',
            'year_of_study' => 'nullable|string|max:50',
            'bio'           => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('profiles', 'public');
        }

        $user->fill($data);
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');

    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('password_success', 'Password updated successfully.');
    }

    // DELETE OWN PROFILE
    public function destroy()
    {
        $user = Auth::user();
        Auth::logout();

        // Optionally, you might want to delete related data here

        $user->delete();

        return redirect('/')->with('success', 'Your profile has been deleted.');
    }
}

    
