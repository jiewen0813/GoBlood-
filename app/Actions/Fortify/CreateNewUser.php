<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'ic_number' => ['required', 'string', 'max:12'],
            'blood_type' => ['required', 'string', 'in:A+,B+,O+,AB+,A-,B-,O-,AB-'],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'dob' => ['required', 'date'], 
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'ic_number' => $input['ic_number'],
            'blood_type' => $input['blood_type'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'dob' => $input['dob'],
        ]);
    }
}
