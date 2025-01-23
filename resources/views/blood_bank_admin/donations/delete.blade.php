@extends('layouts.admin')

@section('title', 'GoBlood | Delete Donation')

@section('header')
<h2 class="font-semibold text-xl text-black leading-tight">
{{ __('Delete Donation') }}
    </h2>
@endsection

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3>Are you sure you want to delete this donation?</h3>

        <p><strong>Donor Name:</strong> {{ $donation->user->name }}</p>
        <p><strong>Donation Quantity:</strong> {{ $donation->quantity }} units</p>
        <p><strong>Event:</strong> {{ $donation->event_name ?? 'No Event' }}</p>
        <p><strong>Donation Date:</strong> {{ $donation->created_at->format('Y-m-d') }}</p>

        <form action="{{ route('blood_bank_admin.donations.destroy', $donation->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">Confirm Deletion</button>
            <a href="{{ route('blood_bank_admin.donations.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
