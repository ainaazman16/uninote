<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }
        if (auth()->check() && auth()->user()->status === 'suspended') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account has been suspended.');
        }


        abort(403, 'Unauthorized');
    }
}
