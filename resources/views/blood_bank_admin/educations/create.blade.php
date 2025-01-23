@extends('layouts.admin')

@section('title', 'GoBlood | Create Educational Resource')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Create New Educational Resource') }}
    </h2>
@endsection

@section('content')
@if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <form action="{{ route('blood_bank_admin.educations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-semibold">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    required placeholder="Enter resource title">
                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-gray-700 font-semibold">Content</label>
                <textarea name="content" id="content" rows="5" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    required placeholder="Enter resource content">{{ old('content') }}</textarea>
                @error('content')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="thumbnail" class="block text-gray-700 font-semibold">Thumbnail (Optional)</label>
                <input type="file" name="thumbnail" id="thumbnail" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    accept="image/*" onchange="previewImage()">
                @error('thumbnail')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image Preview -->
            <div id="imagePreviewContainer" class="mb-4">
                <img id="imagePreview" src="#" alt="Thumbnail Preview" class="hidden w-16 h-16 object-cover rounded-lg">
            </div>

            <div class="flex justify-end">
                <a href="{{ route('blood_bank_admin.educations.index') }}" class="btn btn-secondary mb-3">Cancel</a>
                <button type="submit" class="btn btn-primary mb-3">Create Educational Resource</button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript to preview the selected image
        function previewImage() {
            const file = document.getElementById('thumbnail').files[0];
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
