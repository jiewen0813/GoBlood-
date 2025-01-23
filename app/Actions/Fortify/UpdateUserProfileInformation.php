<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'ic_number' => ['required', 'string', 'size:12'], // New validation rule for IC number
            'blood_type' => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'], // New validation rule for blood type
            'phone' => ['required', 'string', 'max:15'], // New validation rule for phone
            'address' => ['required', 'string', 'max:255'], // New validation rule for address
            'dob' => ['required', 'date'], // New validation rule for date of birth
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // Update additional fields
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'ic_number' => $input['ic_number'], // Include IC number
            'blood_type' => $input['blood_type'], // Include blood type
            'phone' => $input['phone'], // Include phone number
            'address' => $input['address'], // Include address
            'dob' => $input['dob'], // Include date of birth
        ])->save();

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
