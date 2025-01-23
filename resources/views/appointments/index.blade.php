<x-app-layout>
    <x-slot name="title">GoBlood | Appointments</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointments') }}
        </h2>
    </x-slot>

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @if ($isEligible)
        <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-4">Create Appointment</a>
    @else
        <button class="btn btn-secondary mb-4" disabled>You are not eligible to create an appointment yet.</button>
        <p class="text-danger">Please wait 8 weeks after your last donation before making another appointment.</p>
    @endif


        <!-- Upcoming Appointments -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #28a745;">
                <h3 class="m-0 py-2">Upcoming Appointments</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Blood Bank</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                                <td>{{ $appointment->bloodBankAdmin->name }}</td>
                                <td>{{ $appointment->time_slot }}</td>
                                <td>
                                    @if($appointment->status == 'Confirmed')
                                        <span class="badge bg-primary">Confirmed</span>
                                    @elseif($appointment->status == 'Cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @elseif($appointment->status == 'Completed')
                                        <span class="badge bg-success">✓ Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-button">Cancel Appointment</button>
                                    </form>
                                    @if($appointment->appointment_date->toDateString() == now()->toDateString())
                                        @if($appointment->alreadySubmitted)
                                            <!-- Disabled Button for Already Submitted -->
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                Already Submitted
                                            </button>
                                        @else
                                            <!-- Button to open the Health Form modal -->
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#healthFormModal{{ $appointment->id }}">
                                                Fill Health Form
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            <!-- Dynamic Modal for Health Form -->
                            <div class="modal fade" id="healthFormModal{{ $appointment->id }}" tabindex="-1" role="dialog" aria-labelledby="healthFormModalLabel{{ $appointment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="healthFormModalLabel{{ $appointment->id }}">Health Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($appointment->alreadySubmitted)
                                                <!-- Message for Already Submitted -->
                                                <div class="alert alert-info">
                                                    You have already submitted your health details for this appointment.
                                                </div>
                                            @else
                                                <!-- Include the health form partial -->
                                                @include('shared._health_form', ['appointment' => $appointment])
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Past Appointments -->
        <div class="card">
            <div class="card-header text-white" style="background-color: #6c757d;">
                <h3 class="m-0 py-2">Past Appointments</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Blood Bank</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                                <td>{{ $appointment->bloodBankAdmin->name }}</td>
                                <td>{{ $appointment->time_slot }}</td>
                                <td>
                                    @if($appointment->status == 'Confirmed')
                                        <span class="badge bg-primary">Confirmed</span>
                                    @elseif($appointment->status == 'Cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @elseif($appointment->status == 'Completed')
                                        <span class="badge bg-success">✓ Completed</span>
                                    @elseif($appointment->status == 'Not Attended')
                                        <span class="badge bg-secondary">Not Attended</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SweetAlert Integration -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form'); // Find the closest form element

                    Swal.fire({
                        title: 'Are you sure you want to cancel this appointment?',
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
</x-app-layout>
