<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BloodBankAdmin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'blood_bank_admin';

    protected $fillable = ['name', 'username', 'password', 'contact', 'address', 'latitude', 'longtitude'];

    protected $hidden = ['password'];

    public function events()
    {
        return $this->hasMany(BloodDonationEvent::class, 'blood_bank_admin_id');
    }

    public function educations()
    {
        return $this->hasMany(BloodDonationEducation::class, 'blood_bank_admin_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'blood_bank_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'blood_bank_id');
    }
}

