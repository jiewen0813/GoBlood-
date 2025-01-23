@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Delete Blood Donation Event') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Are you sure you want to delete this event?</h3>

        <div class="mb-4">
            <p><strong>Event Name:</strong> {{ $event->eventName }}</p>
            <p><strong>Event Date:</strong> {{ $event->eventDate->format('d-m-Y') }}</p>
            <p><strong>Event Location:</strong> {{ $event->eventLocation }}</p>
            <p><strong>Event Poster:</strong></p>
            @if($event->eventPoster)
                <img src="{{ asset('storage/event_posters/' . basename($event->eventPoster)) }}" alt="Event Poster" class="w-32 h-32 object-cover mb-4">
            @else
                <p>No poster uploaded for this event.</p>
            @endif
        </div>

        <form action="{{ route('blood_bank_admin.blood_donation_events.destroy', $event->eventID) }}" method="POST" class="mt-6">
            @csrf
            @method('DELETE')

            <div class="flex justify-between gap-4">
                <a href="{{ route('blood_bank_admin.blood_donation_events.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-danger">Delete Event</button>
            </div>
        </form>
    </div>
@endsection
