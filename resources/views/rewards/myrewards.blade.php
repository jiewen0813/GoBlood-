<x-app-layout>
    <x-slot name="title">GoBlood | My Rewards</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Rewards') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Points & Rank Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out transform mb-6">
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-star text-warning fs-3 me-2"></i>
                    <h3 class="text-lg fw-bold text-dark">Your Points & Rank</h3>
                </div>
                <p><span class="font-semibold">Total Points:</span> {{ $points }}</p>
                <p class="mb-2"><strong>Rank:</strong> {{ auth()->user()->rank }}</p>
                <a href="{{ route('rewards.index') }}" class="mt-4 btn btn-success">
                    <i class="fas fa-gift mr-2"></i> View Rewards
                </a>
            </div>

            <!-- Rewards Redemptions Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                @if ($redemptions->isEmpty())
                    <p class="text-gray-600">You have not redeemed any rewards yet.</p>
                @else
                    <h3 class="text-lg fw-bold text-dark">Your Rewards</h3>
                    <div class="overflow-x-auto">
                        <table class="table table-striped w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2">Reward Name</th>
                                    <th class="border border-gray-300 px-4 py-2">Points Used</th>
                                    <th class="border border-gray-300 px-4 py-2">Redeemed At</th>
                                    <th class="border border-gray-300 px-4 py-2">QR Code</th>
                                    <th class="border border-gray-300 px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($redemptions as $redemption)
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">{{ $redemption->reward->reward_name }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $redemption->points_used }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $redemption->created_at->format('d M Y H:i') }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @if ($redemption->qr_code_path && Storage::disk('public')->exists($redemption->qr_code_path))
                                                <!-- Render QR Code as an Image -->
                                                <img src="{{ asset('storage/' . $redemption->qr_code_path) }}" alt="QR Code" class="w-16 h-16">
                                            @else
                                                N/A
                                            @endif

                                            @if (!$redemption->is_used)
                                                <form action="{{ route('reward.markAsUsed') }}" method="POST" class="inline mt-2">
                                                    @csrf
                                                    <input type="hidden" name="code" value="{{ $redemption->qr_code }}">
                                                    <button type="submit" class="text-blue-500 underline">
                                                        Click to Use
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @if ($redemption->is_used)
                                                <span class="text-green-600 font-semibold">Used</span><br>
                                            @else
                                                <span class="text-red-600 font-semibold">Unused</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>


    <style>
        .qr-code {
            width: 100px;
            height: 100px;
        }
        .qr-code svg {
            width: 100%;
            height: 100%;
        }
        .qr-code-link:hover {
            text-decoration: none;
        }
    </style>
</x-app-layout>
