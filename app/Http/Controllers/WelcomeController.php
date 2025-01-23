<?php

namespace App\Http\Controllers;

use App\Models\BloodDonationEvent;
use App\Models\BloodBankAdmin;
use App\Models\Inventory;
use App\Models\BloodRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $upcomingEvents = BloodDonationEvent::where('eventDate', '>=', Carbon::today()->toDateString())->get(); // Fetch upcoming events

        $bloodRequests = BloodRequest::where('status', 'Active')->orderBy('created_at', 'desc')->get();

        $bloodBanks = BloodBankAdmin::all();

        $selectedBloodBankId = $request->query('blood_bank_id');

        // Fetch inventory based on the selected blood bank
        $bloodInventory = Inventory::with('bloodBank')
            ->when($selectedBloodBankId, function ($query, $bloodBankId) {
                return $query->where('blood_bank_id', $bloodBankId);
            })
            ->get();

        $locations = $bloodBanks->map(function($bloodBank) {
            return [
                'id' => $bloodBank->id,
                'latitude' => $bloodBank->latitude,
                'longitude' => $bloodBank->longitude,
                'name' => $bloodBank->name,  // If you want to show the name or other info
            ];
        });

        return view('welcome', compact('upcomingEvents', 'bloodRequests', 'locations','bloodBanks', 'bloodInventory', 'selectedBloodBankId')); // Pass events to the view
    }
}
