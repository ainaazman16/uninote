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
    public function handle($request, Closure $next)
{
    $user = auth()->user();

    if (!$user || $user->role !== 'provider') {
        return redirect('/')->with('error', 'Access denied. Providers only.');
    }

    // Check provider record exists and approved
    if (!$user->provider || $user->provider->status !== 'approved') {
        return redirect('/')->with('error', 'Your provider account is not approved yet.');
    }

    return $next($request);
}

}
