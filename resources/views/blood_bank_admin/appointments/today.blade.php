@extends('layouts.admin')

@section('title', 'GoBlood | Today\'s Appointments')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Today\'s Appointments') }}
    </h2>
@endsection  

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('blood_bank_admin.appointments.history') }}" class="btn btn-secondary">
            View Appointment History
        </a>    
    </div>
    @if($appointmentsBySlot->isEmpty())
        <div class="card-body">
            <p class="text-center">No appointments scheduled for today.</p>
        </div>
    @else
        @foreach($appointmentsBySlot as $timeSlot => $appointments)
            <div class="card mb-4">
                <div class="card-header text-white" style="background-color: #007bff;">
                    <h5 class="m-0 py-2">{{ $timeSlot }}</h5>
                </div>
                <div class="card-body">
                    @if($appointments->isEmpty())
                        <p class="text-center">No appointments for today.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Donor Name</th>
                                    <th>IC Number</th>
                                    <th>Phone</th>
                                    <th>Health Details</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->user->name }}</td>
                                        <td>{{ $appointment->user->ic_number }}</td>
                                        <td>{{ $appointment->user->phone }}</td>
                                        <td>
                                            @if($appointment->healthDetail)
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#healthDetailModal{{ $appointment->healthDetail->id }}">
                                                    View 
                                                </button>
                                                <x-health-detail-modal :detail="$appointment->healthDetail" />
                                            @else
                                                <span class="text-muted">Not Submitted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('blood_bank_admin.appointments.updateStatus', $appointment->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Completed">
                                                <button type="submit" class="btn btn-primary btn-sm">Mark as Completed</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
