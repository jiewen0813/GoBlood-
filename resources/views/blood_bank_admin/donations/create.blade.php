@extends('layouts.admin')

@section('title', 'GoBlood | Create Donation')

@section('header')
<h2 class="font-semibold text-xl text-black leading-tight">
{{ __('Create Donation') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <form action="{{ route('blood_bank_admin.donations.store') }}" method="POST">
            @csrf

            <!-- Donor Selection -->
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 font-semibold">Select Donor</label>
                <select name="user_id" id="user_id" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" required>
                    <option value="" disabled selected>--Select Donor--</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->ic_number }}
                        </option>
                    @endforeach
                </select>

                @error('user_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 font-semibold">Quantity (units)</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}"
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full" required>
                @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Event Selection -->
            <div class="mb-4">
                <label for="event_name" class="block text-gray-700 font-semibold">Select Event</label>
                <select name="event_name" id="event_name" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" required>
                    <option value="" disabled selected>--Select an Event--</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->eventName }}" {{ old('event_name') == $event->eventName ? 'selected' : '' }}>
                            {{ $event->eventName }}
                        </option>
                    @endforeach
                </select>
                @error('event_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Blood Bank Information (auto-filled from the logged-in blood bank admin) -->
            <input type="hidden" name="blood_bank_admin_id" value="{{ auth()->guard('blood_bank_admin')->user()->id }}">

            <!-- Submit Button -->
            <div class="flex justify-end">
                <a href="{{ route('blood_bank_admin.donations.index') }}" class="btn btn-secondary mb-3">Cancel</a>
                <button type="submit" class="btn btn-primary mb-3">Add Donation</button>
            </div>
        </form>
    </div>
@endsection
