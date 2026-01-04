<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
     public function index()
    {
        $transactions = Transaction::with('provider.user')
            ->where('student_id', Auth::id())
            ->latest()
            ->get();

        return view('student.transactions.index', compact('transactions'));
    }
}
