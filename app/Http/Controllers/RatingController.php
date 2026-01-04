<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, User $provider)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Rating::updateOrCreate(
            [
                'student_id' => auth()->id(),
                'provider_id' => $provider->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Rating submitted successfully!');
    }
}
