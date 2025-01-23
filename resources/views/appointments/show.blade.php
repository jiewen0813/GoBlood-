<x-app-layout>
    <x-slot name="title">GoBlood | Appointment Details</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card mb-6">
                <div class="card-body">
                    <h5 class="card-title text-xl font-semibold">Appointment Information</h5>
                    <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date }}</p>
                    <p><strong>Time Slot:</strong> {{ $appointment->time_slot }}</p>
                    <p><strong>Blood Bank:</strong> {{ $appointment->bloodBankAdmin->name }}</p>
                    <p><strong>Location:</strong> {{ $appointment->bloodBankAdmin->address }}</p>

                    <!-- If the appointment is in the future, allow the user to edit or cancel -->
                    @if ($appointment->appointment_date >= now()->toDateString())
                        <div class="flex space-x-4 mt-4">
                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Edit Appointment</a>
                            <a href="{{ route('appointments.destroy', $appointment->id) }}" class="btn btn-danger bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="event.preventDefault(); if(confirm('Are you sure you want to cancel this appointment?')) { document.getElementById('delete-form').submit(); }">Cancel Appointment</a>

                            <form id="delete-form" action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @else
                        <p class="text-muted text-gray-500 mt-4">This appointment has already passed.</p>
                    @endif
                </div>
            </div>

            <a href="{{ route('appointments.index') }}" class="btn btn-secondary bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mt-3">Back to Appointments</a>
        </div>
    </div>
</x-app-layout>
