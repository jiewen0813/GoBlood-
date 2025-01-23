<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'GoBlood!'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebarMenu">
        <h3 class="text-lg font-semibold">Admin Menu</h3>
        <div class="admin-info my-3">
            <p>Welcome, <strong>{{ auth()->guard('blood_bank_admin')->user()->username }}</strong></p>
        </div>
        <ul class="mt-4 space-y-2">
            <li><a href="{{ route('blood_bank_admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('blood_bank_admin.inventories.index') }}">Manage Inventory</a></li>
            <li><a href="{{ route('blood_bank_admin.donations.index') }}">Manage Donations</a></li>
            <li><a href="{{ route('blood_bank_admin.blood_donation_events.index') }}">Manage Events</a></li>
            <li><a href="{{ route('blood_bank_admin.educations.index') }}">Manage Educational Resources</a></li>
            <li><a href="{{ route('blood_bank_admin.appointments.today') }}">Manage Appointments</a></li>
            @if(Auth::guard('blood_bank_admin')->check())
                <li>
                    <form method="POST" action="{{ route('blood_bank_admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-red-900">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('blood_bank_admin.login') }}" class="hover:underline">Login</a></li>
                <li><a href="{{ route('blood_bank_admin.register') }}" class="hover:underline">Register</a></li>
            @endif
        </ul>
    </div>

    <!-- Main Content Area -->
    <div id="main">
        <header class="bg-dark-red text-white">
            <div class="container mx-auto flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-xl font-bold">
                    <img src="{{ asset('img/Logo.svg') }}" alt="GoBlood Logo" width="75" height="75">
                </a>
                <!-- Hamburger Menu Icon for Small and Large Screens -->
                <div class="burger-menu" onclick="toggleSidebar()">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
        </header>

        <div class="container mx-auto py-4">
            @yield('header') <!-- Header content -->
            @yield('content') <!-- Main content -->
        </div>
    </div>
</div>

<footer>
<div class="footer-area">
            <div class="container">
                <div class="row d-flex flex-column flex-lg-row justify-content-between">
                    <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                        <h4 class="footer-heading">GoBlood! Blood Donation Management System</h4>
                        <div class="footer-underline"></div>
                    </div>

                    <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                        <h4 class="footer-heading">Quick Links</h4>
                        <div class="footer-underline"></div>
                        <div><a href="/" class="text-white">Home</a></div>
                        <div><a href="/events" class="text-white">Blood Donation Events</a></div>
                        <div><a href="/educations" class="text-white">Educational Resources</a></div>
                    </div>

                    <div class="col-lg-3 col-md-12">
                        <h4 class="footer-heading">Reach Us</h4>
                        <div class="footer-underline"></div>
                        <p><a href="https://fki.ums.edu.my/" target="_blank" class="text-white"><i class="fa fa-map-marker"></i> FKI, Universiti Malaysia Sabah, Kota Kinabalu</p>
                        <a href="https://wa.me/60194570792" target="_blank" class="text-white"><i class="fab fa-whatsapp"></i> +60 19-457-0792</a><br>
                        <a href="mailto:leong_jie_bi21@iluv.ums.edu.my" target="_blank" class="text-white"><i class="fa fa-envelope"></i> leong_jie_bi21@iluv.ums.edu.my</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <p>&copy; 2024 - GoBlood!. All rights reserved.</p>
                    </div>
                    <div class="col-md-4">
                        <div class="social-media">
                            Get Connected:
                            <a href="https://www.facebook.com/jie.wen.77/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/jiewen_0813/?next=%2F" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.linkedin.com/in/leong-jie-wen-b54748222/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</footer>

<!-- Script for Sidebar -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebarMenu');
        const main = document.getElementById('main');

        sidebar.classList.toggle('open'); // Open or close the sidebar
        main.classList.toggle('push'); // Push the main content

        if (sidebar.classList.contains('open')) {
            document.addEventListener('click', handleOutsideClick);
        } else {
            document.removeEventListener('click', handleOutsideClick);
        }
    }

    function handleOutsideClick(event) {
        const sidebar = document.getElementById('sidebarMenu');
        const main = document.getElementById('main');
        const burgerMenu = event.target.closest('.burger-menu');

        // Close sidebar if clicking outside it and not on the burger menu
        if (!sidebar.contains(event.target) && !burgerMenu) {
            sidebar.classList.remove('open');
            main.classList.remove('push');
            document.removeEventListener('click', handleOutsideClick);
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
