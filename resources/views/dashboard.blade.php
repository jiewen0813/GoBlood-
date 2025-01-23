<x-app-layout>
    <x-slot name="title">Donation History</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Donation History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Latest Donation Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out transform">
                <!-- Latest Donation Header with Icon -->
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-tint text-danger fs-3 me-2"></i>
                    <h3 class="text-lg fw-bold text-dark">Your Latest Donation</h3>
                </div>
                @if($latestDonation)
                    <p><span class="font-semibold">Blood Serial No:</span> {{ $latestDonation->blood_serial_no }}</p>
                    <p><span class="font-semibold">Date Donated:</span> {{ $latestDonation->date_donated }}</p>
                    <p><span class="font-semibold">Location:</span> {{ $latestDonation->location }}</p>
                @else
                    <p>You have not made any donations yet.</p>
                @endif
            </div>

            <!-- Latest Donation History Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out transform mt-6">
                <!-- Latest Donation History Header with Icon -->
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-history text-primary fs-3 me-2"></i>
                    <h3 class="text-lg fw-bold text-dark">Donation History</h3>
                </div>
                @if($donations->isNotEmpty())
                    <table class="table table-striped w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2">Date Donated</th>
                                <th class="border border-gray-300 px-4 py-2">Blood Serial No</th>
                                <th class="border border-gray-300 px-4 py-2">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donations as $donation)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">{{ $donation->date_donated }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $donation->blood_serial_no }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $donation->location }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>You have not made any donations yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
