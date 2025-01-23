<x-app-layout>
    <x-slot name="title">GoBlood | Reward Details</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reward Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-xl font-bold text-gray-800 mb-4">{{ $reward->reward_name }}</h1>
                <p><strong>Points Required:</strong> {{ $reward->points_required }}</p>
                <p>{{ $reward->description }}</p>

                @if ($points >= $reward->points_required && $reward->remaining_vouchers > 0)
                    <!-- Confirm Redeem Form -->
                    <form action="{{ route('rewards.confirm', $reward->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-4">Confirm Redeem</button>
                    </form>
                @else
                    <p class="text-red-600 mt-4">You don't have enough points or this reward is out of stock.</p>
                @endif

                <div class="mt-6">
                    <a href="{{ route('rewards.index') }}" class="btn btn-secondary">Back to Rewards</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
