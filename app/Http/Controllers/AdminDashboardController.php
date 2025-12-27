<?php

namespace App\Http\Controllers;
use App\Models\Withdrawal;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
{
    $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();

    return view('admin.dashboard', compact('pendingWithdrawals'));
}
}
