<x-guest-layout class="min-h-screen flex items-center justify-center">
    <x-slot name="title">GoBlood | Blood Bank Admin Login</x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/Logo.svg') }}" alt="GoBlood Logo" width="125" height="125">
            </a>
        </x-slot>

        <h2 class="text-center text-lg font-semibold mb-4">Blood Bank Admin Login</h2>

        <x-validation-errors class="mb-4" style="color: red;" />

        <form method="POST" action="{{ route('blood_bank_admin.login.submit') }}">
            @csrf
            <div>
                <x-label for="username" value="Username" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="mt-4 bg-red-700 hover:bg-red-800 text-white">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
