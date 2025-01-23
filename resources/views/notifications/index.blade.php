<x-app-layout>
    <x-slot name="title">GoBlood | Notifications</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Notifications Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($notifications as $notification)
                    <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out transform">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">New Notification</h3>
                        <p>{{ $notification->data['message'] }}</p>
                        <p class="text-sm text-gray-600 mt-2">Received: {{ $notification->created_at->diffForHumans() }}</p>

                        <!-- Mark as Read Form -->
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-primary">Mark as Read</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">You have no new notifications.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
