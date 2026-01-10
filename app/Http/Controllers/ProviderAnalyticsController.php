<?php

namespace App\Http\Controllers;
use App\Models\Note;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProviderAnalyticsController extends Controller
{
    public function index()
    {
        $provider = Auth::user()->provider;

        // Provider middleware should guarantee this, but guard against null to avoid empty results
        if (! $provider) {
            abort(403, 'Provider profile not found.');
        }

        $providerId = $provider->id;

        // 🔹 Total Earnings
        $totalEarnings = Subscription::where('provider_id', $providerId)
            ->sum('price');

        // 🔹 Monthly Earnings (Last 6 months, zero-filled)
        $startMonth = Carbon::now()->startOfMonth()->subMonths(5);
        $months = collect(range(0, 5))->map(fn ($i) => $startMonth->copy()->addMonths($i));

        $raw = Subscription::where('provider_id', $providerId)
            ->whereBetween('created_at', [$months->first()->copy()->startOfMonth(), $months->last()->copy()->endOfMonth()])
            ->selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, SUM(price) as total')
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get();

        $map = $raw->mapWithKeys(function ($row) {
            $key = sprintf('%04d-%02d', $row->y, $row->m);
            return [$key => (float) $row->total];
        });

        $monthLabels = $months->map(fn ($d) => $d->format('M Y'));
        $monthTotals = $months->map(function ($d) use ($map) {
            $key = sprintf('%04d-%02d', $d->year, $d->month);
            return $map[$key] ?? 0;
        });

        // 🔹 Total Subscribers
        $totalSubscribers = Subscription::where('provider_id', $providerId)
            ->distinct('student_id')
            ->count('student_id');

        // 🔹 Downloads (builder-based to avoid model caching quirks)
        $notesForProvider = Note::query()->where('provider_id', $providerId);

        $topNotes = (clone $notesForProvider)
            ->select(['id', 'title', 'download_count'])
            ->orderByDesc('download_count')
            ->take(5)
            ->get();

        $totalDownloads = (clone $notesForProvider)
            ->sum('download_count');

        return view('provider.analytics.index', compact(
            'totalEarnings',
            'totalSubscribers',
            'topNotes',
            'totalDownloads',
            'monthLabels',
            'monthTotals'
        ));
    }
}
