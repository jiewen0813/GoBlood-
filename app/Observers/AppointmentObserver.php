<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\Donation;

class AppointmentObserver
{
    /**
     * Handle the "updating" event for the Appointment model.
     */
    public function updating(Appointment $appointment)
    {
        // Check if the status is being updated to "completed"
        if ($appointment->isDirty('status') && $appointment->status === 'Completed') {
            // Check if a donation already exists for this appointment
            $donation = Donation::where('appointment_id', $appointment->id)->first();

            if (!$donation) {
                // Create a new donation if it doesn't exist
                Donation::create([
                    'user_id' => $appointment->user_id,
                    'date_donated' => now(),
                    'quantity' => 1, // Default quantity, adjust if needed
                    'blood_serial_no' => $this->generateBloodSerialNo(),
                    'blood_bank_id' => $appointment->blood_bank_id,
                    'appointment_id' => $appointment->id,
                ]);
            } else {
                // Update the existing donation (if needed)
                $donation->update([
                    'date_donated' => now(), // Update date if necessary
                ]);
            }
        }
    }

    /**
     * Generate a unique blood serial number.
     */
    private function generateBloodSerialNo()
    {
        do {
            $serial = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (Donation::where('blood_serial_no', $serial)->exists());

        return $serial;
    }
}
