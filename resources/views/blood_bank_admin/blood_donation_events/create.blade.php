@extends('layouts.admin')

@section('title', 'GoBlood | Create Blood Donation Events')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Create Blood Donation Event') }}
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

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <form action="{{ route('blood_bank_admin.blood_donation_events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="eventName" class="block text-gray-700 font-semibold">Event Name</label>
                <input type="text" name="eventName" id="eventName" value="{{ old('eventName') }}" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    required placeholder="Enter event name">
                @error('eventName')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="eventDate" class="block text-gray-700 font-semibold">Event Date</label>
                <input type="date" name="eventDate" id="eventDate" value="{{ old('eventDate') }}" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    required min="{{ now()->toDateString() }}">
                @error('eventDate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="eventLocation" class="block text-gray-700 font-semibold">Event Location</label>
                <input type="text" name="eventLocation" id="eventLocation" value="{{ old('eventLocation') }}"
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    required placeholder="Enter event location">
                @error('eventLocation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="eventPoster" class="block text-gray-700 font-semibold">Event Poster (Optional)</label>
                <input type="file" name="eventPoster" id="eventPoster" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    onchange="previewImage()">
                @error('eventPoster')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image Preview -->
            <div id="imagePreviewContainer" class="mb-4">
                <img id="imagePreview" src="#" alt="Event Poster Preview" class="hidden w-16 h-16 object-cover rounded-lg">
            </div>

            <div class="flex justify-end">
                <a href="{{ route('blood_bank_admin.blood_donation_events.index') }}" class="btn btn-secondary mb-3">Cancel</a>
                <button type="submit" class="btn btn-primary mb-3">Create Event</button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript to preview the selected image
        function previewImage() {
            const file = document.getElementById('eventPoster').files[0];
            const preview = document.getElementById('imagePreview');
            const previewContainer = document.getElementById('imagePreviewContainer');

            // Check if the file is an image
            if (file && file.type.startsWith('image')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Show the image preview
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden'); // Hide preview if the file is not an image
            }
        }
    </script>
@endsection
