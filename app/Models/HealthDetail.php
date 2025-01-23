<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'eventID',
        'appointment_id',
        'responses',
        'source_type',
    ];

    protected $casts = [
        'responses' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(BloodDonationEvent::class, 'eventID');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }

}
