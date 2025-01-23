<x-app-layout>
    <x-slot name="title">GoBlood | Home</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Swiper Section -->
    <div class="py-0">
        <div class="w-full mx-auto sm:px-4 lg:px-4">
            <div class="p-6">
                <div class="swiper-container relative mb-8">
                    <div class="swiper-wrapper">
                        @foreach($upcomingEvents as $event)
                            <div class="swiper-slide flex items-center justify-center">
                                <div class="text-center">
                                    <a href="{{ route('events.index') }}">
                                        <img src="{{ Storage::url($event->eventPoster) }}" alt="Event Poster" class="event-poster rounded-lg shadow-lg" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next absolute top-1/2 right-5 transform -translate-y-1/2 z-10"></div>
                    <div class="swiper-button-prev absolute top-1/2 left-5 transform -translate-y-1/2 z-10"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video and Request Section -->
    <div style="display: flex; height: 50vh;">
        <!-- Left Column (Video) -->
        <div style="flex: 1; display: flex; align-items: center; justify-content: center; background-color: #ffffff;">
            <video autoplay muted loop style="width: 80%; height: auto; max-width: 400px; max-height: 300px;">
                <source src="{{ asset('img/Video.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        
        <!-- Right Column -->
        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
            <h1 style="font-weight: bold; font-size: 36px;">Blood Request</h1>
            <p>In need of blood? Don't hesitate to request from our blood donor.</p>
            <a href="{{ route('blood_requests.create') }}" class="btn btn-primary btn-lg">Request Now!</a>
        </div>
    </div>

    <!-- Blood Requests Section -->
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($bloodRequests as $request)
                <div class="bg-white shadow-md rounded-lg p-4 text-sm w-72 mx-auto">
                    <h3 class="text-lg font-semibold mb-2 text-red-600">Blood Type: {{ $request->blood_type }}</h3>
                    <p><strong>IC Number:</strong> {{ $request->ic_number }}</p>
                    <p><strong>Quantity:</strong> {{ $request->quantity }}</p>
                    <p><strong>Location:</strong> {{ $request->location }}</p>
                    <p><strong>Requested:</strong> {{ $request->created_at->diffForHumans() }}</p>
                    @if($request->notes)
                        <p><strong>Notes:</strong> {{ $request->notes }}</p>
                    @endif
                    <a href="https://wa.me/{{ str_replace('+', '', $request->phone) }}" 
                    target="_blank" 
                    class="text-green-500 mt-2 block">
                        Message on WhatsApp
                    </a>
                </div>
            @empty
                <p class="text-gray-500">No active blood requests available at the moment.</p>
            @endforelse
        </div>
    </div>

    <!-- Blood Bank Selection and Inventory -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="GET" action="{{ route('welcome') }}">
                <label for="blood_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Select a Blood Bank:</label>
                <select 
                    name="blood_bank_id" 
                    id="blood_bank_id" 
                    class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm mb-4" 
                    onchange="this.form.submit()">
                    <option value="">-- Select Blood Bank --</option>
                    @foreach ($bloodBanks as $bloodBank)
                        <option value="{{ $bloodBank->id }}" {{ request('blood_bank_id') == $bloodBank->id ? 'selected' : '' }}>
                            {{ $bloodBank->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Show Inventory Only When a Blood Bank is Selected -->
            @if (request('blood_bank_id'))
                @if ($bloodInventory->isNotEmpty())
                    <h3 class="text-lg font-semibold text-gray-800 mt-4">Blood Stocks</h3>
                    <div class="row">
                        @foreach($bloodInventory as $inventory)
                            <div class="col-md-3 mb-4">
                                <div class="card text-center shadow">
                                    <div class="card-body">
                                        <!-- Blood Drop SVG -->
                                        <div class="blood-drop-container">
                                            <svg width="60" height="90" viewBox="0 0 30 42">
                                                <path fill="transparent" stroke="#000" stroke-width="1.5"
                                                    d="M15 3
                                                        Q16.5 6.8 25 18
                                                        A12.8 12.8 0 1 1 5 18
                                                        Q13.5 6.8 15 3z" />
                                                <defs>
                                                    <clipPath id="blood-drop-clip-{{ $inventory->id }}">
                                                        <path d="M15 3
                                                                Q16.5 6.8 25 18
                                                                A12.8 12.8 0 1 1 5 18
                                                                Q13.5 6.8 15 3z" />
                                                    </clipPath>
                                                </defs>
                                                <rect x="0" y="{{ 42 - ($inventory->quantity / 100) * 42 }}" width="30" 
                                                    height="{{ ($inventory->quantity / 100) * 42 }}" 
                                                    fill="red" clip-path="url(#blood-drop-clip-{{ $inventory->id }})" class="blood-fill" />
                                            </svg>
                                        </div>

                                        <!-- Blood Group -->
                                        <h4 class="mt-3">{{ $inventory->blood_type }}</h4>

                                        <!-- Quantity -->
                                        <p>{{ $inventory->quantity }} units</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 mt-4">No inventory available for the selected blood bank.</p>
                @endif
            @else
                <p class="text-gray-500 mt-4">Please select a blood bank to view the inventory.</p>
            @endif
        </div>
    </div>

    <!-- Map Section -->
    <div class="w-full mx-auto px-4 lg:px-8 mb-12 mt-8">
        <div id="map" style="height: 400px; border-radius: 8px;"></div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>    
    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });

        // Initialize Leaflet map
        const map = L.map('map').setView([5.9804, 116.0719], 13);

        // Add the tile layer to the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add markers for each blood bank location
        const locations = @json($locations);

        locations.forEach(function(location) {
            L.marker([location.latitude, location.longitude])
                .addTo(map)
                .bindPopup(`
                    <b>${location.name}</b><br>
                    Latitude: ${location.latitude}<br>
                    Longitude: ${location.longitude}<br>
                    <a href="https://www.google.com/maps?q=${location.latitude},${location.longitude}" target="_blank" class="text-blue-500">Open in Google Maps</a>
                `);
        });
    </script>
</x-app-layout>
