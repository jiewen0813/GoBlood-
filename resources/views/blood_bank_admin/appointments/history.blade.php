@extends('layouts.admin')

@section('title', 'GoBlood | Appointment History')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Appointment History') }}
    </h2>
@endsection  

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @if($pastAppointments->isEmpty())
        <div class="card-body">
            <p class="text-center">No appointment history available.</p>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #6c757d;">
                <h5 class="m-0 py-2">Past Appointments</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Donor Name</th>
                            <th>IC Number</th>
                            <th>Status</th>
                            <th>Health Questionnaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                                <td>{{ $appointment->time_slot }}</td>
                                <td>{{ $appointment->user->name }}</td>
                                <td>{{ $appointment->user->ic_number }}</td>
                                <td>{{ $appointment->status }}</td>
                                <td>
                                    @if($appointment->healthDetail)
                                        <!-- Button to trigger modal -->
                                        <button 
                                            class="btn btn-primary view-health-details-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#healthDetailModal{{ $appointment->healthDetail->id }}">
                                            View Questionnaire
                                        </button>

                                        <!-- Modal Component -->
                                        <x-health-detail-modal :detail="$appointment->healthDetail" />
                                    @else
                                        <span class="text-muted">Not submitted</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
