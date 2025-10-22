@extends('layouts.admin')

@section('title', 'GoBlood | Donation History')

@section('header')
<h2 class="font-semibold text-xl text-black leading-tight">
    {{ __('Donation History') }}
</h2>
@endsection

@section('content')

@if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('blood_bank_admin.donations.create') }}" class="btn btn-primary">Add New Donation</a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="table-responsive">
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Donor IC</th>
                        <th class="px-4 py-2 border-b">Blood Serial No.</th> 
                        <th class="px-4 py-2 border-b">Quantity (units)</th>
                        <th class="px-4 py-2 border-b">Event/Appointment</th>
                        <th class="px-4 py-2 border-b">Donation Type</th>
                        <th class="px-4 py-2 border-b">Donation Date</th>
                        <th class="px-4 py-2 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $donation->user->ic_number ?? 'Unknown Donor' }}</td>
                            <td class="px-4 py-2 border-b">{{ $donation->blood_serial_no ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $donation->quantity }}</td>
                            <td class="px-4 py-2 border-b">
                                @if($donation->event)
                                    {{ $donation->event->eventName ?? 'No Event Name' }}
                                @elseif($donation->appointment)
                                    Appointment - {{ $donation->appointment->time_slot ?? 'N/A' }}
                                @else
                                    No Event or Appointment
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b">
                                @if ($donation->appointment_id)
                                    Appointment
                                @else
                                    Walk-in
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b">{{ $donation->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 border-b">
                                @if (!$donation->appointment_id)
                                    <a href="{{ route('blood_bank_admin.donations.edit', $donation->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                                 <!-- Delete Form -->
                                <form action="{{ route('blood_bank_admin.donations.destroy', $donation->id) }}" method="POST" style="display: inline-block;" id="delete-form-{{ $donation->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-button" data-donation-id="{{ $donation->id }}">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $donations->links() }}  <!-- Pagination links -->
        </div>
    </div>
    
    <script>
        // Add SweetAlert2 confirmation for delete actions
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const donationId = this.getAttribute('data-donation-id'); // Get the donation ID
                    const form = document.getElementById(`delete-form-${donationId}`); // Find the closest form element

                    // Show SweetAlert confirmation dialog
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
