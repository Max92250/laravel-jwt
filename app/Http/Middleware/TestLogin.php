<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class TestLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        // Check if the user is authenticated and has a valid Sanctum token
        if (Auth::check() && $request->user()->tokens->isNotEmpty()) {
            // Check if the user's role matches the specified role
            if ($request->user()->hasRole($role)) {
                // If the roles match, proceed with the request
                return $next($request);
            }
        }

        // If not authenticated or token is not present, redirect to login
        return redirect('login')->with('success', 'Please log in.');
    }
}

