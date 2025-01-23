@extends('layouts.admin')

@section('title', 'GoBlood | Edit Educational Resource')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Edit Educational Resource') }}
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
        <form action="{{ route('blood_bank_admin.educations.update', $education->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-semibold">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $education->title) }}" 
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
                    required placeholder="Enter resource content">{{ old('content', $education->content) }}</textarea>
                @error('content')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="thumbnail" class="block text-gray-700 font-semibold">Thumbnail (Optional)</label>
                <input type="file" name="thumbnail" id="thumbnail" 
                    class="mt-1 p-2 border border-gray-300 rounded-lg w-full"
                    onchange="previewImage()">
                @error('thumbnail')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Current Thumbnail Preview -->
                <div id="imagePreviewContainer" class="mt-4">
                    <img id="imagePreview" src="{{ $education->thumbnail ? asset('storage/' . $education->thumbnail) : '#' }}" 
                         alt="Thumbnail Preview" 
                         class="{{ $education->thumbnail ? '' : 'hidden' }} w-16 h-16 object-cover rounded-lg">
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('blood_bank_admin.educations.index') }}" class="btn btn-secondary mb-3 mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary mb-3">Update Resource</button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript to preview and replace the current image with the new one
        function previewImage() {
            const file = document.getElementById('thumbnail').files[0];
            const preview = document.getElementById('imagePreview');

            // Check if the file is an image
            if (file && file.type.startsWith('image')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Show the new image
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
