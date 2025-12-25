<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('profile.edit');
    }

    // UPDATE OWN PROFILE
    public function update(Request $request)
    {
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

    
