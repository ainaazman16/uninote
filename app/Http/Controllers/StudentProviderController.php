<?php

namespace App\Http\Controllers;

use App\Models\User;

class StudentProviderController extends Controller
{
    public function show(User $user)
    {
        // Ensure only approved providers are visible
        if ($user->role !== 'provider') {
            abort(404);
        }

        return view('student.providers.show', compact('user'));
    }
}
