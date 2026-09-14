<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Display the login page
    public function showLoginForm()
    {
        // If the user is already logged in, redirect them directly to their dashboard
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect admin to Admin Dashboard
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            } else {
                // Redirect employee to Employee Dashboard
                return redirect('/employee/dashboard');
            }
        }

        // If not logged in, show the login view
        return view('auth.login');
    }

    // 2. Handle login form submission
    public function login(Request $request)
    {
        // Validate input fields (cannot be empty)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Check credentials against the database
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            
            $user = Auth::user();

            // Check if the user account is deactivated (inactive)
            if ($user->status != 'active') {
                Auth::logout(); // Log out immediately
                return back()->with('error', 'Your account is deactivated. Please contact your administrator.');
            }

            // If Admin logs in
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Welcome Admin!');
            } 
            // If Employee logs in
            else {
                return redirect('/employee/dashboard')->with('success', 'Welcome ' . $user->name);
            }

        } else {
            // If email or password does not match
            return back()->with('error', 'Invalid email or password!');
        }
    }

    // 3. Handle user logout
    public function logout()
    {
        Auth::logout(); // Log out the user
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
