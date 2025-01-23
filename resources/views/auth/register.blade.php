<x-guest-layout>
<x-slot name="title">GoBlood | Register</x-slot>
    <x-authentication-card>
        <x-slot name="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/Logo.svg') }}" alt="GoBlood Logo" width="125" height="125">
            </a>
        </x-slot>

        <x-validation-errors class="mb-4" />

        <h2 class="text-center text-lg font-semibold mb-4">User Registration</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="ic_number" value="{{ __('IC Number') }}" />
                <x-input id="ic_number" class="block mt-1 w-full" type="text" name="ic_number" :value="old('ic_number')" required autocomplete="ic_number" placeholder="Enter 12-digit IC number without '-'"/>
            </div>

            <div class="mt-4">
                <x-label for="blood_type" value="{{ __('Blood Type') }}" />
                <select id="blood_type" name="blood_type" class="block mt-1 w-full" required>
                    <option value="" disabled selected>Select Blood Type</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>

            <div class="mt-4">
                <x-label for="phone" value="{{ __('Phone') }}" />
                <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone" placeholder="Enter phone number without '-'"/>
            </div>

            <div class="mt-4">
                <x-label for="address" value="{{ __('Address') }}" />
                <textarea id="address" class="block mt-1 w-full" name="address" required autocomplete="address">{{ old('address') }}</textarea>
            </div>

            <div class="mt-4">
                <x-label for="dob" value="{{ __('Date of Birth') }}" />
                <x-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob')" required autocomplete="dob" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4 bg-red-700 hover:bg-red-800 text-white">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
