<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Inventory;
use App\Models\User;
use App\Notifications\LowBloodStockNotification;

class CheckBloodInventory
{
    public function handle($request, Closure $next)
    {
        $lowStocks = Inventory::where('quantity', '<', 10)->get();

        foreach ($lowStocks as $stock) {
            $bloodBank = $stock->bloodBank->name;
            $bloodType = $stock->blood_type;
            $quantity = $stock->quantity;

            // Notify users
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new LowBloodStockNotification($bloodBank, $bloodType, $quantity));
            }
        }

        return $next($request);
    }
}
