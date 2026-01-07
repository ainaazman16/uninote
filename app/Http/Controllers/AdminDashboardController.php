<?php

namespace App\Http\Controllers;
use App\Models\Withdrawal;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use App\Models\WalletTopup;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
{
     // USERS BY ROLE
    $userLabels = ['Students', 'Providers', 'Admins'];
    $userData = [
        User::where('role', 'student')->count(),
        User::where('role', 'provider')->count(),
        User::where('role', 'admin')->count(),
    ];

    // MONTHLY TOPUPS (LAST 6 MONTHS)
    $months = collect(range(5, 0))->map(function ($i) {
        return Carbon::now()->subMonths($i)->format('M');
    });

     $topupData = collect(range(5, 0))->map(function ($i) {
        return WalletTopup::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->subMonths($i)->month)
            ->sum('amount');
    });

    //DAILY TOPUPS (LAST 7 DAYS)
    $days = collect(range(6, 0))->map(function ($i) {
        return Carbon::now()->subDays($i)->format('d M');
    });

    $dailyTopupData = collect(range(6, 0))->map(function ($i) {
        return WalletTopup::where('status', 'approved')
            ->whereDate('created_at', Carbon::now()->subDays($i)->toDateString())
            ->sum('amount');
    });

    // Sum earnings per provider
        $earnings = Subscription::select(
                'provider_id',
                DB::raw('SUM(price) as total_earned')
            )
            ->groupBy('provider_id')
            ->orderByDesc('total_earned')
            ->get();

        $providerLabels = [];
        $providerEarnings = [];

        foreach ($earnings as $row) {
            $provider = User::find($row->provider_id);

            $providerLabels[] = $provider?->name ?? 'Unknown Provider';
            $providerEarnings[] = $row->total_earned;
        }

    $subscriptionCount = Subscription::count();

     $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();

    $pendingTopups = WalletTopup::where('status', 'pending')->count();

    return view('admin.dashboard', compact(
        'pendingWithdrawals',
        'pendingTopups',
        'subscriptionCount',
        'userLabels',
        'userData',
        'months',
        'topupData',
        'days',
        'dailyTopupData',
        'providerLabels',
        'providerEarnings'
    ));
}
}
