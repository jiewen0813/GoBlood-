<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodRequest;
use App\Models\User;
use App\Notifications\NewBloodRequest;

class BloodRequestController extends Controller
{
    // Show logged-in user's blood requests
    public function index()
    {
        $userId = Auth::id();

        // Fetch only non-completed requests
        $activeRequests = BloodRequest::where('user_id', $userId)
            ->where('status', '=', 'Active')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blood_requests.index', compact('activeRequests'));
    }

    // Show form to create a blood request
    public function create()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a blood request.');
        }

        // Render the create view if authenticated
        return view('blood_requests.create');
    }

    // Store a new blood request
    public function store(Request $request)
    {
        $userId = Auth::id(); // Get the authenticated user's ID

        // Determine the validation rules based on request_type
        $rules = [
            'request_type' => 'required|string|in:self,other', // Restrict to valid options
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'ic_number' => 'nullable|string',
        ];

        if ($request->request_type === 'other') {
            $rules['ic_number'] = 'required|string|max:15';
            $rules['blood_type'] = 'required|string|max:3'; // Required for "other"
            $rules['phone'] = [
                'required',
                'string',
                'regex:/^(?:\\+60|0)[1-9]\\d{7,9}$/', // Validate Malaysian phone numbers
                'max:15',
            ];
        }

        $request->validate($rules);

        // Determine phone and blood_type based on request_type
        $icNumber = $request->request_type === 'self' ? Auth::user()->ic_number : $request->ic_number;
        $phone = $request->request_type === 'self' ? Auth::user()->phone : $request->phone;
        $bloodType = $request->request_type === 'self' ? Auth::user()->blood_type : $request->blood_type;

        // Create the blood request
        $bloodRequest = BloodRequest::create([
            'user_id' => $userId,
            'request_type' => $request->request_type,
            'blood_type' => $bloodType,
            'quantity' => $request->quantity,
            'location' => $request->location,
            'phone' => $phone,
            'notes' => $request->notes,
            'ic_number' => $icNumber,
            'status' => 'Active',
        ]);

        // Send notifications to all users except the one who made the request
        $users = User::where('id', '!=', $userId)->get(); // Exclude the requester
        foreach ($users as $user) {
            $user->notify(new NewBloodRequest($bloodRequest));
        }

        return redirect()->route('blood_requests.index')->with('success', 'Your blood request has been submitted!');
    }

    // Update the status of a blood request
    public function updateStatus(Request $request, $id)
    {
        $userId = Auth::id(); // Get the authenticated user's ID

        $request->validate([
            'status' => 'required|string|in:Active,Completed,Cancelled',
        ]);

        $bloodRequest = BloodRequest::findOrFail($id);

        // Verify that the logged-in user owns the request
        if ($bloodRequest->user_id !== $userId) {
            return redirect()->back()->with('error', 'You are not authorized to update this request.');
        }

        // Update the status
        $bloodRequest->status = $request->status;
        $bloodRequest->save();

        return redirect()->route('blood_requests.index')->with('success', 'Status updated successfully!');
    }
}

