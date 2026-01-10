<?php

namespace App\Http\Controllers;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class ProviderRatingAnalyticsController extends Controller
{
    public function index()
    {
        // Use the provider profile id (not the user id) to fetch owned notes
        $provider = Auth::user()->provider;

        $notes = Note::where('provider_id', $provider->id)
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->with([
                'ratings' => function ($q) {
                    $q->selectRaw('note_id, rating, COUNT(*) as total')
                      ->groupBy('note_id', 'rating');
                }
            ])
            ->get();

        return view('provider.analytics.ratings', compact('notes'));
    }
}

