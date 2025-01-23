<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Point;

class DonationObserver
{
    /**
     * Handle the "created" event for the Donation model.
     *
     * @param  \App\Models\Donation  $donation
     * @return void
     */
    public function created(Donation $donation)
    {
        // Get the user associated with the donation
        $user = $donation->user; // Ensure the 'Donation' model has a 'user' relationship

        // Add points to the user's total
        $point = Point::firstOrCreate(['user_id' => $user->id]);
        $point->total_points += 50; // Add 50 points
        $point->save();
    }

    public function deleting(Donation $donation)
    {
        $user = $donation->user;

        $point = Point::where('user_id', $user->id)->first();

        if ($point) {
            $point->total_points -= 50; // Deduct 50 points
            $point->total_points = max($point->total_points, 0); // Ensure it doesn't go below zero
            $point->save();
        }
    }
}