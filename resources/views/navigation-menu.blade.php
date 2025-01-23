<nav x-data="{ open: false, loginOpen: false, notificationOpen: false, profileOpen: false }" class="bg-dark-red text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('img/Logo.svg') }}" alt="GoBlood Logo" width="125" height="125">
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex space-x-4 ml-auto">
                    <a href="{{ url('/events') }}" class="nav-link text-white hover:text-gray-400">Events</a>
                    <a href="{{ url('/educations') }}" class="nav-link text-white hover:text-gray-400">Educational Resources</a>

                    @auth
                        <a href="{{ url('/appointments') }}" class="nav-link text-white hover:text-gray-400">Appointment</a>
                        <a href="{{ url('/blood-requests') }}" class="nav-link text-white hover:text-gray-400">Blood Requests</a>
                    @endauth
                </div>
            </div>

            <!-- User Profile Dropdown (aligned to the right) -->
            <div class="flex items-center ml-auto space-x-8">
                @auth
                    <!-- Bell Icon with Notifications -->
                    <div class="relative inline-block text-sm mr-4" @click.outside="notificationOpen = false">
                        <button class="relative text-white hover:text-gray-400" id="notificationBell" @click="notificationOpen = !notificationOpen">
                            <i class="fa fa-bell"></i>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 inline-block w-4 h-4 bg-red-600 text-white text-xs font-bold text-center rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div
                            x-show="notificationOpen"
                            class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-50 text-black notification-dropdown"
                            style="width: 325px; display: none; left: 50%; transform: translateX(-50%);"                        >
                            <h6 class="px-4 py-2 font-semibold text-gray-700 bg-gray-100">Notifications</h6>
                            <div class="py-2 notification-content">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="block px-4 py-2 text-xs hover:bg-gray-200">
                                        {{ $notification->data['message'] }}
                                        <small class="block text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                @empty
                                    <div class="px-4 py-2 text-xs text-gray-500">No new notifications</div>
                                @endforelse
                            </div>
                            <div class="border-t">
                                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-xs text-center text-gray-700 hover:bg-gray-200">
                                    View All Notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative inline-block text-sm" @click.outside="profileOpen = false">
                        <button class="profile-name hover:text-gray-400 flex items-center space-x-2" @click="profileOpen = !profileOpen">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fa fa-caret-down"></i>
                        </button>

                        <div
                            x-show="profileOpen"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-50 text-black"
                            style="display: none;"
                        >
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm hover:bg-gray-200">Profile</a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-200">My Donation History</a>
                            <a href="{{ route('rewards.myrewards') }}" class="block px-4 py-2 text-sm hover:bg-gray-200">My Rewards</a>
                            <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-200">
                                @csrf
                                <button type="submit" class="text-sm">Log Out</button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Login and Register Buttons (only shown if not authenticated) -->
                @guest
                    <div class="relative inline-block text-sm ml-4">
                        <button @click="loginOpen = !loginOpen" class="nav-link text-white hover:text-gray-400">Login</button>
                        <div x-show="loginOpen" class="absolute z-10 w-48 bg-gray-800 text-white rounded-md mt-1" @click.away="loginOpen = false" style="display: none;">
                            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-700">User Login</a>
                            <a href="{{ route('blood_bank_admin.login') }}" class="block px-4 py-2 hover:bg-gray-700">Blood Bank Login</a>
                        </div>
                    </div>

                    @if (Route::has('register'))
                        <div class="relative inline-block text-sm ml-4">
                            <a href="{{ route('register') }}" class="nav-link text-white hover:text-gray-400">Register</a>
                        </div>
                    @endif
                @endguest
            </div>

            <!-- Burger Menu Icon for Mobile -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="text-white p-2 rounded-md focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ url('/events') }}" class="block px-4 py-2 text-white hover:bg-gray-700">Events</a>
            <a href="{{ url('/educations') }}" class="block px-4 py-2 text-white hover:bg-gray-700">Educational Resources</a>
            @auth
                <a href="{{ url('/appointments') }}" class="block px-4 py-2 text-white hover:bg-gray-700">Appointment</a>
                <a href="{{ url('/blood-requests') }}" class="block px-4 py-2 text-white hover:bg-gray-700">Blood Requests</a>
            @endauth
        </div>
    </div>


    <style>
        .notification-dropdown {
            max-height: 300px;
            overflow-y: auto;
        }
        .notification-content {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>

    <script>
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.nav-link') && !event.target.closest('#notificationBell')) {
                document.querySelectorAll('.dropdown-content').forEach((dropdown) => {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>
</nav>
