<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="photo" value="{{ __('Profile Photo') }}" />

            <!-- Current Profile Photo -->
            <div class="mt-2">
                <img src="{{ $this->user->profile_photo_url }}" alt="" class="rounded-full h-20 w-20 object-cover">
            </div>

            <!-- Upload New Profile Photo -->
            <input type="file" id="photo" class="mt-2 block w-full" wire:model="photo" accept="image/*">
            <x-input-error for="photo" class="mt-2" />

            <!-- Remove Profile Photo -->
            @if ($this->user->profile_photo_path)
                <x-button secondary class="mt-2" wire:click="deleteProfilePhoto">
                    {{ __('Remove Photo') }}
                </x-button>
            @endif
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- IC Number -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="ic_number" value="{{ __('IC Number') }}" />
            <x-input id="ic_number" type="text" class="mt-1 block w-full" wire:model="state.ic_number" required placeholder="Enter 12-digit IC number without '-'" />
            <x-input-error for="ic_number" class="mt-2" />
        </div>

        <!-- Blood Type -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="blood_type" value="{{ __('Blood Type') }}" />
            <select id="blood_type" wire:model="state.blood_type" class="mt-1 block w-full" required>
                <option value="" disabled>Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
            <x-input-error for="blood_type" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Phone') }}" />
            <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="state.phone" required placeholder="Enter phone number without '-'" />
            <x-input-error for="phone" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="address" value="{{ __('Address') }}" />
            <textarea id="address" class="mt-1 block w-full" wire:model="state.address" required></textarea>
            <x-input-error for="address" class="mt-2" />
        </div>

        <!-- Date of Birth -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="dob" value="{{ __('Date of Birth') }}" />
            <x-input id="dob" type="date" class="mt-1 block w-full" wire:model="state.dob" required />
            <x-input-error for="dob" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
