<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type',
        'blood_type',
        'quantity',
        'location',
        'phone',
        'notes',
        'status',
        'ic_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
