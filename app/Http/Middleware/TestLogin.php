<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has a valid Sanctum token
        if (auth()->check() && $request->user()->tokens->isNotEmpty()) {
            return $next($request);
        }

        // If not authenticated or token is not present, redirect to login
        return redirect('login')->with('success', 'Please log in.');
    }
}

