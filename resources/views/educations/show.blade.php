<x-app-layout>
    <x-slot name="title">GoBlood | Educational Resources</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Education Resource: ') }} {{ $education->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Adding hover effect to the card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out">
                <!-- Display the title of the education resource -->
                <h3 class="text-2xl font-semibold">{{ $education->title }}</h3>
                
                <!-- Display the thumbnail image if it exists -->
                @if($education->thumbnail)
                    <div style="display: flex; justify-content: center;">
                        <img src="{{ asset('storage/' . $education->thumbnail) }}" alt="Thumbnail" class="rounded-lg" style="width: 500px; height: auto;">
                    </div>
                @endif
                
                <!-- Display the content of the education resource -->
                <div class="mt-4">
                    <p>{!! nl2br(e($education->content)) !!}</p>
                </div>

                <!-- Display creation date (optional) -->
                <div class="mt-4 text-sm text-gray-500">
                    <p>Created by: {{ $education->bloodBankAdmin->name ?? 'Unknown' }}</p>
                    <p>Created on: {{ $education->created_at->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
