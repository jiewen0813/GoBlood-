<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = ['reward_name', 'points_required', 'voucher_limit', 'remaining_vouchers', 'description'];

    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reward) {
            $reward->remaining_vouchers = $reward->voucher_limit;
        });

        static::updating(function ($reward) {
            // Optionally reset remaining_vouchers if voucher_limit is updated
            if ($reward->isDirty('voucher_limit')) {
                $reward->remaining_vouchers = $reward->voucher_limit;
            }
        });
    }
}
