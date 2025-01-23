<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ic_number',
        'blood_type',
        'phone',
        'address',
        'dob',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the URL of the user's profile photo or a default image.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : asset('images/default-profile.png'); // Use a default image if no profile photo is uploaded
    }

    /**
     * Update the user's profile photo.
     *
     * @param \Illuminate\Http\UploadedFile $photo
     * @return void
     */
    public function updateProfilePhoto($photo)
    {
        // Delete old photo if it exists
        if ($this->profile_photo_path) {
            Storage::disk('public')->delete($this->profile_photo_path);
        }

        // Store new profile photo
        $path = $photo->store('profile-photos', 'public');

        // Update the profile_photo_path in the database
        $this->forceFill(['profile_photo_path' => $path])->save();
    }

    public function setPhoneAttribute($value)
    {
        // Check if the phone number starts with a "0" and prepend +60 (Malaysia's country code)
        if (substr($value, 0, 1) === '0') {
            $this->attributes['phone'] = '+60' . substr($value, 1);  // Remove leading '0' and add '+60'
        } else {
            $this->attributes['phone'] = $value;
        }
    }

    /**
     * Define a relationship to the Donation model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'user_id');
    }

    public function latestDonationDate()
    {
        return $this->donations()->latest()->first()?->dateDonated;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function healthDetails()
    {
        return $this->hasMany(HealthDetail::class, 'user_id');
    }

    public function points()
    {
        return $this->hasOne(Point::class, 'user_id');
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function addPoints($amount)
    {
        $point = $this->points()->firstOrCreate([]);
        $point->total_points += $amount;
        $point->save();
    }

    public function deductPoints($amount)
    {
        $point = $this->points()->first();

        if ($point && $point->total_points >= $amount) {
            $point->total_points -= $amount;
            $point->save();

            return true;
        }

        return false; // Not enough points
    }

    public function getTotalPointsAttribute()
    {
        return $this->points->total_points ?? 0;
    }

    public function redemptions()
    {
        return $this->hasMany(Redemption::class, 'user_id');
    }

    public function getRankAttribute()
    {
        $donationCount = $this->donations()->count(); // Count total donations

        if ($donationCount >= 20) {
            return 'Gold';
        } elseif ($donationCount >= 10) {
            return 'Silver';
        } elseif ($donationCount >= 5) {
            return 'Bronze';
        } else {
            return 'New Donor';
        }
    }



}
