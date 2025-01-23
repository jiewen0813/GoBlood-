<x-app-layout>
    <x-slot name="title">GoBlood | Rewards</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rewards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- My Rewards Button -->
            <div class="mb-6">
                <a href="{{ route('rewards.myrewards') }}" class="btn btn-secondary">
                    Back to My Rewards
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Rewards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rewards as $reward)
                    <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out transform">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $reward->reward_name }}</h3>
                        <p><strong>Points Required:</strong> {{ $reward->points_required }}</p>
                        <p>{{ $reward->description }}</p>

                        <form action="{{ route('rewards.redeem', $reward->id) }}" method="GET">
                            <button type="submit" class="btn btn-primary mt-4">Redeem</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
