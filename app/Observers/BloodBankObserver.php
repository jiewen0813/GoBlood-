<?php

namespace App\Observers;

use App\Models\BloodBankAdmin;
use App\Models\Inventory;

class BloodBankObserver
{
    /**
     * Handle the BloodBankAdmin "created" event.
     *
     * @param  \App\Models\BloodBankAdmin  $bloodBankAdmin
     * @return void
     */
    public function created(BloodBankAdmin $bloodBankAdmin)
    {
        // List of all blood types
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        // Create inventory records for each blood type
        foreach ($bloodTypes as $type) {
            Inventory::create([
                'blood_bank_id' => $bloodBankAdmin->id, // Link to the new BloodBankAdmin
                'blood_type' => $type,
                'quantity' => 0, // Default initial quantity
            ]);
        }
    }
}