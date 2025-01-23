<?php 

namespace App\Http\Controllers;

use App\Models\BloodDonationEvent;
use App\Models\User;
use App\Models\HealthDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Notifications\NewBloodDonationEvent;

class BloodDonationEventController extends Controller
{
    // Display list of events, separated into upcoming and past
    public function index()
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        $upcomingEvents = BloodDonationEvent::where('blood_bank_admin_id', $bloodBankAdminId)
                                            ->where('eventDate', '>=', Carbon::today())
                                            ->get();

        $pastEvents = BloodDonationEvent::where('blood_bank_admin_id', $bloodBankAdminId)
                                        ->where('eventDate', '<', Carbon::today())
                                        ->get();

        return view('blood_bank_admin.blood_donation_events.index', compact('upcomingEvents', 'pastEvents'));
    }

    // Show form to create a new event
    public function create()
    {
        return view('blood_bank_admin.blood_donation_events.create');
    }

    // Store a new event in the database
    public function store(Request $request)
    {
        $request->validate([
            'eventName' => 'required|string|max:50',
            'eventDate' => 'required|date',
            'eventLocation' => 'required|string|max:50',
            'eventPoster' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        // Store the event poster if uploaded
        $filePath = $request->file('eventPoster') ? $request->file('eventPoster')->store('event_posters', 'public') : null;

        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        $event = BloodDonationEvent::create([
            'eventName' => $request->eventName,
            'eventDate' => $request->eventDate,
            'eventLocation' => $request->eventLocation,
            'eventPoster' => $filePath, // Save relative path
            'blood_bank_admin_id' => $bloodBankAdminId,
        ]);
        // Send the notification to all users
        $users = User::all(); // You can filter users if necessary
        foreach ($users as $user) {
            $user->notify(new NewBloodDonationEvent($event)); 
        }

        return redirect()->route('blood_bank_admin.blood_donation_events.index')
                        ->with('success', 'Event created successfully.');
    }

    public function edit($eventID)
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        $event = BloodDonationEvent::where('eventID', $eventID)
                                   ->where('blood_bank_admin_id', $bloodBankAdminId)
                                   ->firstOrFail();

        return view('blood_bank_admin.blood_donation_events.edit', compact('event'));
    }

    public function update(Request $request, $eventID)
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Find the event and ensure it's associated with the logged-in admin
        $event = BloodDonationEvent::where('eventID', $eventID)
                                ->where('blood_bank_admin_id', $bloodBankAdminId)
                                ->firstOrFail();

        $validated = $request->validate([
            'eventName' => 'required|string|max:50',
            'eventDate' => 'required|date',
            'eventLocation' => 'required|string|max:50',
            'eventPoster' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Temporary variable to store the old poster path
        $oldPosterPath = $event->eventPoster;

        // Handle event poster update if a new file is uploaded
        if ($request->hasFile('eventPoster')) {
            // Store the new file
            $filePath = $request->file('eventPoster')->store('event_posters', 'public');
            $event->eventPoster = $filePath; // Update to the new file path
        }

        // Update other event details
        $event->eventName = $validated['eventName'];
        $event->eventDate = $validated['eventDate'];
        $event->eventLocation = $validated['eventLocation'];

        // Save all changes to the event, including the new poster path
        if ($event->save() && $request->hasFile('eventPoster')) {
            // Only delete the old poster after the new file has been successfully saved
            if ($oldPosterPath) {
                Storage::disk('public')->delete($oldPosterPath);
            }
        }

        return redirect()->route('blood_bank_admin.blood_donation_events.index')
                        ->with('success', 'Event updated successfully!');
    }


    public function destroy($eventID)
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Find the event and make sure it's associated with the logged-in admin
        $event = BloodDonationEvent::where('eventID', $eventID)
                                ->where('blood_bank_admin_id', $bloodBankAdminId)
                                ->firstOrFail();

        // Handle deletion of the poster
        if ($event->eventPoster) {
            Storage::disk('public')->delete($event->eventPoster); // Directly use the relative path
        }

        $event->delete();

        return redirect()->route('blood_bank_admin.blood_donation_events.index')
                        ->with('success', 'Event deleted successfully.');
    }

    public function viewHealthDetailsByEvent($eventID)
    {
        $healthDetails = HealthDetail::where('eventID', $eventID)
            ->with('user')
            ->get();

        $event = BloodDonationEvent::findOrFail($eventID);

        return view('blood_bank_admin.health_details.index', compact('healthDetails', 'event'));
    }

    public function showHealthDetail($eventID, $healthDetailID)
    {
        $healthDetail = HealthDetail::where('eventID', $eventID)->where('id', $healthDetailID)->firstOrFail();
        $healthDetail->responses = json_decode($healthDetail->responses, true); 

        return response()->json($healthDetail);
    }

    public function searchHealthDetails(Request $request, $eventID)
    {
        $event = BloodDonationEvent::findOrFail($eventID);

        $query = $event->healthDetails()->with('user');

        if ($request->has('ic_number') && $request->ic_number != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('ic_number', 'LIKE', '%' . $request->ic_number . '%');
            });
        }

        $healthDetails = $query->get();

        return view('blood_bank_admin.health_details.index', compact('event', 'healthDetails'));
    }


}
