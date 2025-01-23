@extends('layouts.admin')

@section('title', 'GoBlood | Manage Events')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Blood Donation Events') }}
    </h2>
@endsection  

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <a href="{{ route('blood_bank_admin.blood_donation_events.create') }}" class="btn btn-primary mb-3">Add New Event</a>

    <!-- Upcoming Events Card -->
    <div class="card mb-4">
        <div class="card-header text-white" style="background-color: #28a745;">
            <h3 class="m-0 py-2">Upcoming Events</h3>
        </div>

        @if($upcomingEvents->isEmpty())
            <p>No upcoming events.</p>
        @else
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Poster</th>
                            <th>Health Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingEvents as $event)
                            <tr>
                                <td>{{ $event->eventName }}</td>
                                <td>{{ $event->eventDate->format('d-m-Y') }}</td>
                                <td>{{ $event->eventLocation }}</td>
                                <td>
                                    @if($event->eventPoster)
                                        <img src="{{ Storage::url($event->eventPoster) }}" alt="Event Poster" width="100">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Link to view health details for this specific event -->
                                    <a href="{{ route('blood_bank_admin.health_details.index', ['eventID' => $event->eventID]) }}" class="btn btn-info">
                                        View Health Details
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('blood_bank_admin.blood_donation_events.edit', $event->eventID) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('blood_bank_admin.blood_donation_events.destroy', $event->eventID) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Past Events Card -->
    <div class="card">
        <div class="card-header text-white" style="background-color: #6c757d;">
            <h3 class="m-0 py-2">Past Events</h3>
        </div>

        @if($pastEvents->isEmpty())
            <p>No past events.</p>
        @else
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Health Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastEvents as $event)
                            <tr>
                                <td>{{ $event->eventName }}</td>
                                <td>{{ $event->eventDate->format('d-m-Y') }}</td>
                                <td>{{ $event->eventLocation }}</td>
                                <td>
                                    <!-- Link to view health details for this specific event -->
                                    <a href="{{ route('blood_bank_admin.health_details.index', ['eventID' => $event->eventID]) }}" class="btn btn-info">
                                        View Health Details
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('blood_bank_admin.blood_donation_events.edit', $event->eventID) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('blood_bank_admin.blood_donation_events.destroy', $event->eventID) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form'); // Find the closest form element

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    });
</script>

@endsection
