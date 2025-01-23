<x-app-layout>
    <x-slot name="title">Go Blood | My Blood Requests</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Blood Requests') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <a href="{{ route('blood_requests.create') }}" class="btn btn-primary mb-4">Request Now!</a>
        
        <!-- Active Requests -->
        <div class="container bg-white p-6 sm:rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">Active Blood Requests</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($activeRequests as $request)
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-red-600">Blood Type: {{ $request->blood_type }}</h3>
                        <p><strong>IC Number:</strong> {{ $request->ic_number }}</p>
                        <p><strong>Quantity:</strong> {{ $request->quantity }}</p>
                        <p><strong>Location:</strong> {{ $request->location }}</p>
                        <p><strong>Status:</strong> {{ $request->status }}</p>
                        <p><strong>Requested:</strong> {{ $request->created_at->diffForHumans() }}</p>

                        <!-- Update Status Form -->
                        <form action="{{ route('blood_requests.updateStatus', $request->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mt-2">
                                <label for="status_{{ $request->id }}" class="block font-semibold">Update Status:</label>
                                <select name="status" id="status_{{ $request->id }}" class="form-control w-full" required>
                                    <option value="Active" {{ $request->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Completed" {{ $request->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $request->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Status</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">You have no active blood requests at the moment.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
