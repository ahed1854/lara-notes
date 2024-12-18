<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HandleAuthNotifications
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user just logged in
        if ($request->user() && !Session::has('login_shown')) {
            Session::flash('success', 'Welcome back, ' . $request->user()->name . '!');
            Session::put('login_shown', true);
            
            // Redirect to dashboard after login
            if ($request->is('login')) {
                return redirect()->route('dashboard');
            }
        }

        // Check if user just logged out
        if (Session::has('logged_out') && !Session::has('logout_shown')) {
            Session::flash('success', 'You have been logged out successfully.');
            Session::put('logout_shown', true);
        }

        return $next($request);
    }
} 