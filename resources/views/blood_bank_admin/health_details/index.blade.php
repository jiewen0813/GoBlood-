@extends('layouts.admin')

@section('title', 'Health Details for Event')

@section('content')
    <h2>Health Details for {{ $event->eventName }}</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('blood_bank_admin.health_details.search', $event->eventID) }}" class="mb-3">
        <div class="input-group" style="max-width: 400px;">
            <input 
                type="text" 
                name="ic_number" 
                class="form-control" 
                placeholder="Search by IC Number" 
                value="{{ request('ic_number') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Health Details Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User Name</th>
                <th>IC Number</th>
                <th>Submission Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($healthDetails as $detail)
                <tr>
                    <td>{{ $detail->user->name }}</td>
                    <td>{{ $detail->user->ic_number }}</td>
                    <td>{{ $detail->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <!-- Button to Trigger Modal -->
                        <button 
                            class="btn btn-primary view-health-details-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#healthDetailModal{{ $detail->id }}">
                            View Health Details
                        </button>
                    </td>
                </tr>

                <!-- Include Health Modal Component -->
                <x-health-detail-modal :detail="$detail" />
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Dynamic Modal Content Handling
    document.querySelectorAll('.btn-primary[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalId);

            // Update modal title dynamically
            const userName = this.closest('tr').querySelector('td:first-child').textContent;
            modal.querySelector('.modal-title').textContent = `Health Details for ${userName}`;
        });
    });

    // Reset Modal Content on Close
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            // Reset dynamic content inside modal
            this.querySelector('.modal-title').textContent = 'Health Details';
            this.querySelectorAll('.dynamic-content').forEach(el => el.innerHTML = '');
        });
    });
});

</script>
@endsection
