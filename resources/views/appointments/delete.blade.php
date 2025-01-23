<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Delete Appointment') }}
        </h2>
    </x-slot>

    <div class="container py-8">
        <div class="alert alert-warning">
            <strong>Warning!</strong> Are you sure you want to delete this appointment?
        </div>

        <div class="form-group">
            <p><strong>Appointment Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
            <p><strong>Time Slot:</strong> {{ $appointment->time_slot }}</p>
            <p><strong>Blood Bank:</strong> {{ $appointment->bloodBank->name }}</p>
        </div>

        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <!-- Confirmation Buttons -->
            <button type="submit" class="btn btn-danger">Yes, Delete Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
