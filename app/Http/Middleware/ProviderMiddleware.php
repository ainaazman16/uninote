<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProviderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    if (!$user || $user->role !== 'provider') {
        return redirect('/login');
    }

    if (!$user->provider) {
        return redirect('/dashboard')->with('error', 'Provider profile not found.');
    }

    if ($user->provider->status !== 'approved') {
        return redirect('/dashboard')->with('error', 'Provider account not approved yet.');
    }

    return $next($request);
}

    

}
