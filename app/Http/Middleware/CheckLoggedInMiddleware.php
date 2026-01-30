<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckLoggedInMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() && !auth('admin')->check()) {
            redirect()->setIntendedUrl($request->fullUrl());
            return redirect()->route('auth.login')->with('error', 'Please log in to access this page.');
        }
        if (auth()->check() && (auth()->user()->status ?? '') !== 'active') {
            auth()->logout();
            return redirect()->route('auth.login')->with('error', 'Your account is not active. Please contact the administrator.');
        }
        return $next($request);
    }
}
