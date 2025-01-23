<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\LowBloodLevelNotification;
use App\Models\User;

class Inventory extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'inventories';

    // The attributes that are mass assignable.
    protected $fillable = [
        'blood_bank_id',  // The blood bank the inventory belongs to
        'blood_type',     // The blood type (A+, A-, B+, B-, etc.)
        'quantity',       // The quantity of blood available
    ];

    public function bloodBank()
    {
        return $this->belongsTo(BloodBankAdmin::class, 'blood_bank_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'blood_bank_id', 'blood_bank_id')
            ->whereColumn('blood_type', 'blood_type');
    }

    /**
     * Increment the inventory quantity by a given value.
     *
     * @param int $quantity
     * @return void
     */
    public function incrementStock(int $quantity)
    {
        $this->quantity += $quantity;
        $this->save(); // Save the updated quantity
    }

    /**
     * Decrement the inventory quantity by a given value.
     *
     * @param int $quantity
     * @return void
     */
    public function decrementStock(int $quantity)
    {
        // Ensure the quantity doesn't go below zero
        if ($this->quantity - $quantity >= 0) {
            $this->quantity -= $quantity;
            $this->save(); // Save the updated quantity
        } else {
            throw new \Exception('Insufficient stock to decrement.');
        }
    }

    protected static function booted()
    {
        // Listen for the updated event
        static::updated(function ($inventory) {
            if ($inventory->quantity < 10) {
                $bloodBank = $inventory->bloodBank->name;
                $bloodType = $inventory->blood_type;
                $quantity = $inventory->quantity;

                // Notify all users
                $users = User::all();
                foreach ($users as $user) {
                    $user->notify(new LowBloodLevelNotification($bloodBank, $bloodType, $quantity));
                }
            }
        });
    }
}
