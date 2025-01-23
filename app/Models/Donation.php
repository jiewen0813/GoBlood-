<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'donations';

    // The attributes that are mass assignable
    protected $fillable = [
        'blood_serial_no',
        'date_donated',
        'quantity',
        'user_id',
        'blood_bank_id',
        'event_id',
        'appointment_id'
    ];

    // The relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodBankAdmin()
    {
        return $this->belongsTo(BloodBankAdmin::class, 'blood_bank_id');
    }

    public function event()
    {
        return $this->belongsTo(BloodDonationEvent::class, 'event_id', 'eventID');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'blood_type', 'blood_type')
            ->whereColumn('blood_bank_id', 'blood_bank_id');
    }

}
