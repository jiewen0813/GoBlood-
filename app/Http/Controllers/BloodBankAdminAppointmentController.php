<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment; 
use App\Models\BloodBankAdmin;
use App\Models\HealthDetail;
use Carbon\Carbon;

class BloodBankAdminAppointmentController extends Controller
{
    public function todayAppointments()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $cutoffTime = $today->copy()->setTime(17, 0, 0);
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Update status to 'Not Attended' for overdue appointments
        Appointment::where('blood_bank_id', $bloodBankAdminId)
            ->where('status', 'Confirmed')
            ->where(function ($query) use ($today, $cutoffTime, $now) {
                $query->whereDate('appointment_date', '<', $today) // Past dates
                    ->orWhere(function ($q) use ($today, $cutoffTime, $now) {
                        // Today's appointments overdue (past 5 PM)
                        $q->whereDate('appointment_date', '=', $today)
                            ->whereTime('time_slot', '<=', $cutoffTime->format('H:i:s'))
                            ->whereTime('time_slot', '<=', $now->format('H:i:s'));
                    });
            })
            ->update(['status' => 'Not Attended']);

        // Fetch today's appointments with 'Confirmed' or 'Not Attended' status
        $todayAppointments = Appointment::where('blood_bank_id', $bloodBankAdminId)
            ->whereDate('appointment_date', $today)
            ->whereIn('status', ['Confirmed', 'Not Attended']) // Include overdue appointments
            ->orderBy('time_slot')
            ->get();

        // Group appointments by time slot
        $appointmentsBySlot = $todayAppointments->groupBy('time_slot');

        // Pass the grouped appointments to the view
        return view('blood_bank_admin.appointments.today', compact('appointmentsBySlot'));
    }



    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Confirmed,Cancelled,Completed,Not Attended',
        ]);

        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();
        $appointment = Appointment::findOrFail($id);

        // Check if the appointment belongs to the current blood bank admin
        if ($appointment->blood_bank_id != $bloodBankAdminId) {
            abort(403, 'Unauthorized action.');
        }

        // Update the appointment status
        $appointment->status = $validated['status'];
        $appointment->save();

        // Redirect to the donations index page if marked as Completed
        if ($validated['status'] === 'Completed') {
            return redirect()->route('blood_bank_admin.donations.index')
                             ->with('success', 'Appointment completed. View donations.');
        }

        return redirect()->back()->with('success', 'Appointment status updated.');
    }

    public function history()
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Fetch past appointments sorted by date descending
        $pastAppointments = Appointment::where('blood_bank_id', $bloodBankAdminId)
            ->whereDate('appointment_date', '<', now())
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('blood_bank_admin.appointments.history', compact('pastAppointments'));
    }

    public function showTodayAppointmentHealthDetail($appointment_id, $healthDetailID)
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Fetch the appointment and ensure it belongs to the authenticated admin
        $appointment = Appointment::where('blood_bank_id', $bloodBankAdminId)
            ->where('id', $appointment_id)
            ->with('healthDetail') // Load the healthDetail relationship
            ->firstOrFail();

        // Ensure the healthDetail exists and matches the ID in the route
        if (!$appointment->healthDetail || $appointment->healthDetail->id != $healthDetailID) {
            abort(404, 'Health detail not found or does not belong to this appointment.');
        }

        // Return a view to display the health detail
        return view('blood_bank_admin.health_details.show', [
            'appointment' => $appointment,
            'healthDetail' => $appointment->healthDetail,
        ]);
    }

}