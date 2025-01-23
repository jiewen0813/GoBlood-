<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BloodBankAdmin;
use App\Models\User;
use App\Models\BloodDonationEvent;
use App\Models\Appointment;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Show the list of donations for the blood bank admin.
     */
    public function index()
    {
        // Get the logged-in blood bank admin's ID
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Fetch donations linked to the logged-in admin's associated blood bank
        $donations = Donation::where('blood_bank_id', $bloodBankAdminId)
            ->with(['user', 'event', 'appointment']) // Eager load relationships
            ->latest() // Order by the most recent donations
            ->paginate(10); // Paginate results

        return view('blood_bank_admin.donations.index', compact('donations'));
    }

    /**
     * Show the form to create a new donation.
     */
    public function create()
    {
        // Get the logged-in blood bank admin's ID
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        $users = User::all(); 
        $events = BloodDonationEvent::where('blood_bank_admin_id', $bloodBankAdminId)
            ->whereDate('eventDate', Carbon::today())
            ->get();

        $appointments = Appointment::where('blood_bank_id', $bloodBankAdminId)
            ->where('status', 'completed')
            ->get();  // Completed appointments
        $bloodBanks = BloodBankAdmin::all();  // Assuming you have a BloodBank model

        // Pass the data to the view
        return view('blood_bank_admin.donations.create', compact('users', 'events', 'appointments', 'bloodBanks'));
    }

    /**
     * Store a new donation in the database and update the inventory.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'event_name' => 'nullable|exists:blood_donation_events,eventName',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        // Get the event for today if the event is selected
        $event = $request->event_name ? BloodDonationEvent::where('eventName', $request->event_name)->first() : null;

        // Check if the blood bank admin is logged in and retrieve the blood bank ID
        $blood_bank_id = auth()->guard('blood_bank_admin')->id();

        // Generate a 9-digit blood serial number
        $blood_serial_no = $this->generateBloodSerialNo();

        // Create a new donation record
        $donation = Donation::create([
            'user_id' => $request->user_id,
            'date_donated' => Carbon::now(),
            'quantity' => $request->quantity,
            'blood_serial_no' => $blood_serial_no,
            'blood_bank_id' => $blood_bank_id,
            'event_name' => $event ? $event->eventName : null,
            'event_id' => $event ? $event->eventID : null,
            'appointment_id' => $request->appointment_id,
        ]);

        // Fetch the user's blood type from the users table
        $user = User::find($request->user_id);
        $bloodType = $user->blood_type;  // Get the blood type from the user

        if ($event) {
            // If the donation is linked to an event, update the inventory
            $this->updateInventory($blood_bank_id, $bloodType, $donation->quantity);
        }

        // Check if the associated appointment exists and is completed
        if ($donation->appointment && $donation->appointment->status === 'Completed') {
            // Update the inventory
            $this->updateInventory($blood_bank_id, $bloodType, $donation->quantity);
        }

        return redirect()->route('blood_bank_admin.donations.index')->with('status', 'Donation added successfully!');
    }

    /**
     * Generate a unique 9-digit blood serial number.
     */
    private function generateBloodSerialNo()
    {
        return str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
    }

    /**
     * Update inventory based on donation details.
     */
    private function updateInventory($bloodBankAdminId, $bloodType, $quantity)
    {
        $inventory = Inventory::where('blood_bank_id', $bloodBankAdminId)
            ->where('blood_type', $bloodType)
            ->first();

        if ($inventory) {
            $inventory->quantity += $quantity;
            $inventory->save();
        } else {
            Inventory::create([
                'blood_bank_id' => $bloodBankAdminId,
                'blood_type' => $bloodType,
                'quantity' => $quantity,
            ]);
        }
    }

    /**
     * Show the form to edit an existing donation.
     */
    public function edit(Donation $donation)
    {
        // Ensure the logged-in blood bank admin is associated with the donation
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Get the required data for the edit form (users, events, etc.)
        $users = User::all();
        $events = BloodDonationEvent::where('blood_bank_admin_id', $bloodBankAdminId)
            ->whereDate('eventDate', Carbon::today())
            ->get();

        return view('blood_bank_admin.donations.edit', compact('donation', 'users', 'events'));
    }

    /**
     * Update the specified donation in the database.
     */
    public function update(Request $request, Donation $donation)
    {
        // Validate the incoming request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'event_name' => 'nullable|exists:blood_donation_events,eventName',
        ]);

        // Get the event for today if the event is selected
        $event = $request->event_name ? BloodDonationEvent::where('eventName', $request->event_name)->first() : null;

        // Ensure the logged-in blood bank admin is associated with the donation
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Update the donation record with the new data
        $donation->update([
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'event_name' => $event ? $event->eventName : null,
            'event_id' => $event ? $event->eventID : null, // Ensure event_id is updated if an event is selected
        ]);

        return redirect()->route('blood_bank_admin.donations.index')->with('status', 'Donation updated successfully!');
    }

    /**
     * Remove the specified donation from the database.
     */
   
    public function destroy(Donation $donation)
    {
        // Ensure the logged-in blood bank admin is associated with the donation
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Check if the donation belongs to the logged-in blood bank admin
        if ($donation->blood_bank_id !== $bloodBankAdminId) {
            return redirect()->route('blood_bank_admin.donations.index')->with('error', 'Unauthorized access to this donation.');
        }

        // Fetch the user's blood type and quantity from the donation
        $user = User::find($donation->user_id);
        $bloodType = $user->blood_type; // Get the blood type from the user
        $quantity = $donation->quantity;

        // Decrease the inventory
        $this->decreaseInventory($bloodBankAdminId, $bloodType, $quantity);

        // Delete the donation record
        $donation->delete();

        return redirect()->route('blood_bank_admin.donations.index')->with('status', 'Donation deleted successfully!');
    }

    /**
     * Decrease inventory based on donation details.
     */
    private function decreaseInventory($bloodBankAdminId, $bloodType, $quantity)
    {
        $inventory = Inventory::where('blood_bank_id', $bloodBankAdminId)
            ->where('blood_type', $bloodType)
            ->first();

        if ($inventory) {
            // Ensure the inventory doesn't go below zero
            if ($inventory->quantity >= $quantity) {
                $inventory->quantity -= $quantity;
                $inventory->save();
            } else {
                throw new \Exception('Inventory stock is insufficient to decrease.');
            }
        }
    }

    /**
     * Check if a donor is eligible to donate blood.
     */
    private function isEligibleToDonate($userId)
    {
        $lastDonation = Donation::where('user_id', $userId)
            ->latest('date_donated')
            ->first();

        if ($lastDonation) {
            $eightWeeksAgo = Carbon::now()->subWeeks(8);
            return $lastDonation->date_donated <= $eightWeeksAgo;
        }

        return true; // Eligible if no prior donations
    }

}
