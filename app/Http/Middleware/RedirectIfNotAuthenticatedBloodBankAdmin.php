<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticatedBloodBankAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated as a blood bank admin
        if (!Auth::guard('blood_bank_admin')->check()) {
            // Redirect to the blood bank admin login page
            return redirect()->route('blood_bank_admin.login');
        }

        // Allow the request to proceed
        return $next($request);
    }
}
