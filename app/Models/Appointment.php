<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'user_id',
        'blood_bank_id',
        'appointment_date',
        'time_slot',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    /**
     * Get the user (blood donor) that makes the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the blood bank admin associated with the appointment.
     */
    public function bloodBankAdmin()
    {
        return $this->belongsTo(BloodBankAdmin::class, 'blood_bank_id');
    }
    
    public function donation()
    {
        return $this->hasOne(Donation::class);
    }

    public function healthDetail()
    {
        return $this->hasOne(HealthDetail::class, 'appointment_id', 'id');
    }


    /**
     * Check if a specific time slot is fully booked for a given blood bank and date.
     *
     * @param int $bloodBankId
     * @param string $appointmentDate
     * @param string $timeSlot
     * @return bool
     */
    public static function isSlotFullyBooked($bloodBankId, $appointmentDate, $timeSlot)
    {
        $appointmentCount = self::where('blood_bank_id', $bloodBankId)
            ->whereDate('appointment_date', $appointmentDate)
            ->where('time_slot', $timeSlot)
            ->count();

        return $appointmentCount >= 5;
    }

    /**
     * Check if the appointment is confirmed.
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status === 'Confirmed';
    }

    /**
     * Check if the appointment is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === 'Completed';
    }

    /**
     * Check if the appointment is cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === 'Cancelled';
    }
}
