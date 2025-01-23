<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'GoBlood' }}</title>


        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen" style="background-color: #ffffff;">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0t6u9VXfjA5xqz5PA5SguGzmYgfe9pa9SFIJt6b+k4jjsjkA" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Leaflet JS -->
        <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>        
        
        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
    </body>

    
</html>
