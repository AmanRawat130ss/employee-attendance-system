<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. If user is not logged in, redirect directly to login page
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        $user = Auth::user();

        // 2. If user account is deactivated (inactive)
        if ($user->status != 'active') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is deactivated.');
        }

        // 3. If user role does not match the required role
        // (For example, an employee trying to access admin pages)
        if ($user->role != $role) {
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/employee/dashboard')->with('error', 'Notice: Full company reports are managed by Administrators. Please sign in as Admin to generate organizational reports.');
            }
        }

        // Everything is fine, allow request to proceed
        return $next($request);
    }
}
