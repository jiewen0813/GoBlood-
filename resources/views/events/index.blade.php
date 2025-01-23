<x-app-layout>
    <x-slot name="title">GoBlood | Events</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
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
        <!-- Upcoming Events -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #28a745;">
                <h3 class="m-0 py-2">Upcoming Events</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Health Questionnaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingEvents as $event)
                            <tr>
                                <td>{{ $event->eventName }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->eventDate)->format('Y-m-d') }}</td>
                                <td>{{ $event->eventLocation }}</td>
                                <td>
                                    @if($event->alreadySubmitted)
                                        <!-- Disabled Button for Already Submitted -->
                                        <button class="btn btn-secondary" disabled>
                                            Submitted
                                        </button>
                                    @elseif(\Carbon\Carbon::parse($event->eventDate)->isToday() && $event->isEligible)
                                        <!-- Button to trigger modal (Eligible and Event is Today) -->
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#healthFormModal{{ $event->eventID }}">
                                            Register as Walk-In Donor
                                        </button>
                                    @elseif(\Carbon\Carbon::parse($event->eventDate)->isToday() && !$event->isEligible)
                                        <!-- Not Eligible -->
                                        <button class="btn btn-secondary" disabled>
                                            Not Eligible
                                        </button>
                                        <p class="text-danger small">You must wait 8 weeks after your last donation.</p>
                                    @else
                                        <!-- Event Date is in the Future -->
                                        <span class="text-muted">Available on {{ \Carbon\Carbon::parse($event->eventDate)->format('Y-m-d') }}</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal for Health Form -->
                            <div class="modal fade" id="healthFormModal{{ $event->eventID }}" tabindex="-1" role="dialog" aria-labelledby="healthFormModalLabel{{ $event->eventID }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="healthFormModalLabel{{ $event->eventID }}">Health Questionnaire for {{ $event->eventName }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($event->alreadySubmitted)
                                                <!-- Message for Already Submitted -->
                                                <div class="alert alert-info">
                                                    You have already submitted your health details for this event.
                                                </div>
                                            @else
                                                <!-- Include the health form -->
                                                @include('shared._health_form', ['event' => $event])
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

        <!-- Past Events -->
        <div class="card">
            <div class="card-header text-white" style="background-color: #6c757d;">
                <h3 class="m-0 py-2">Past Events</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastEvents as $event)
                            <tr>
                                <td>{{ $event->eventName }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->eventDate)->format('Y-m-d') }}</td>
                                <td>{{ $event->eventLocation }}</td>
                                <td>
                                    <span class="text-muted">Event Ended</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
