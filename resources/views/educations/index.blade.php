<x-app-layout>
    <x-slot name="title">GoBlood | Educational Resources</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Educational Resources') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Display educational resources -->
            @if($educations->isEmpty())
                <p>No educational resources available.</p>
            @else
                <!-- Tailwind grid system -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($educations as $education)
                        <div class="card mb-6 hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out">
                            @if($education->thumbnail)
                                <img src="{{ asset('storage/' . $education->thumbnail) }}" alt="Thumbnail" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="No Thumbnail" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $education->title }}</h5>
                                <a href="{{ route('educations.show', $education->id) }}" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
