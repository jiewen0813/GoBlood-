<?php

namespace App\Models;

use App\Models\BloodBankAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BloodDonationEvent extends Model
{
    use HasFactory;

    protected $table = 'blood_donation_events';

    protected $primaryKey = 'eventID';

    protected $fillable = [
        'eventName',
        'eventDate',
        'eventLocation',
        'eventPoster',
        'blood_bank_admin_id',
    ];

    // Cast eventDate as a datetime (Carbon instance)
    protected $casts = [
        'eventDate' => 'datetime',
    ];

    // Relationship with BloodBankAdmin
    public function bloodBankAdmin()
    {
        return $this->belongsTo(BloodBankAdmin::class, 'blood_bank_admin_id');
    }

    // Scope for upcoming events only
    public function scopeUpcoming($query)
    {
        return $query->where('eventDate', '>=', Carbon::now()->format('Y-m-d'));
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'event_id');
    }

    public function healthDetails()
    {
        return $this->hasMany(HealthDetail::class, 'eventID');
    }


}

