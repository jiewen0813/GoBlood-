<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodBankAdmin;

class BloodBankAdminController extends Controller
{
    // Show the login form for blood bank admins
    public function showLoginForm()
    {
        return view('auth.blood_bank_admin_login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::guard('blood_bank_admin')->attempt($request->only('username', 'password'))) {
            // If successful, redirect to the intended location
            return redirect()->route('blood_bank_admin.dashboard'); // Change this to your dashboard route
        }

        // If unsuccessful, redirect back to the login form with an error message
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('blood_bank_admin')->logout(); // Log the blood bank admin out
        return redirect('/'); // Redirect to the home page or login page
    }
}
