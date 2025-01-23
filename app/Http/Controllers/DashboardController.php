<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard with the latest donation and donation history.
     */
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user

        // Get the latest donation
        $latestDonation = Donation::where('user_id', $user->id)
                                  ->with(['event', 'bloodBankAdmin'])
                                  ->latest('date_donated')
                                  ->first();

        // Format the latest donation details if it exists
        if ($latestDonation) {
            $latestDonation->date_donated = $latestDonation->date_donated 
                                            ? Carbon::parse($latestDonation->date_donated)->format('d M Y')
                                            : null;

            if ($latestDonation->event) {
                $latestDonation->location = $latestDonation->event->eventName; 
            } elseif ($latestDonation->bloodBankAdmin) {
                $latestDonation->location = $latestDonation->bloodBankAdmin->name; 
            } else {
                $latestDonation->location = 'Unknown Location'; 
            }
        }

        // Get all donation history
        $donations = Donation::where('user_id', $user->id)
                             ->with(['event', 'bloodBankAdmin'])
                             ->orderBy('date_donated', 'desc')
                             ->get();

        // Format donation history
        $donations->each(function ($donation) {
            $donation->date_donated = $donation->date_donated 
                                      ? Carbon::parse($donation->date_donated)->format('d M Y') 
                                      : null;

            if ($donation->event) {
                $donation->location = $donation->event->eventName;
            } elseif ($donation->bloodBankAdmin) {
                $donation->location = $donation->bloodBankAdmin->name; 
            } else {
                $donation->location = 'Unknown Location'; 
            }
        });

        return view('dashboard', compact('latestDonation', 'donations'));
    }
}

