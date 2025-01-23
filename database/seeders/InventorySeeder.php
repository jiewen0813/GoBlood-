<?php

namespace Database\Seeders;

use App\Models\BloodBankAdmin;
use App\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        // Get all blood banks (blood bank admins)
        $bloodBanks = BloodBankAdmin::all();

        // Loop through each blood bank and create inventory records for each blood type
        foreach ($bloodBanks as $bloodBank) {
            foreach ($bloodTypes as $bloodType) {
                // Insert each blood type with an initial quantity of 0
                Inventory::create([
                    'blood_bank_id' => $bloodBank->id,
                    'blood_type' => $bloodType,
                    'quantity' => 0, // Initial quantity set to 0
                ]);
            }
        }
    }
}
