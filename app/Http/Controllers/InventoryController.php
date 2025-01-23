<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User;
use App\Notifications\LowBloodLevelNotification;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a list of inventories for the logged-in blood bank.
     */
    public function index()
    {
        // Fetch all inventory records for the logged-in blood bank admin's blood bank
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Get the inventory records for the blood bank
        $inventories = Inventory::where('blood_bank_id', $bloodBankAdminId)->get();

        return view('blood_bank_admin.inventories.index', compact('inventories'));
    }

    /**
     * Show the form for editing the inventory's quantity.
     */
    public function edit(Inventory $inventory)
    {
        return view('blood_bank_admin.inventories.edit', compact('inventory'));
    }

    /**
     * Update the inventory quantity manually (increment or decrement).
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'action' => 'required|in:increment,decrement',
        ]);

        try {
            if ($request->action === 'increment') {
                $inventory->quantity += $request->quantity;
            } elseif ($request->action === 'decrement') {
                if ($inventory->quantity >= $request->quantity) {
                    $inventory->quantity -= $request->quantity;
                } else {
                    return redirect()->back()->with('error', 'Insufficient stock to decrement.');
                }
            }

            // Save the updated inventory
            $inventory->save();

            return redirect()->route('blood_bank_admin.inventories.index')->with('status', 'Inventory updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

}
