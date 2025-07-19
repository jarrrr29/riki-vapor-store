<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riki Vapor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-900 text-white font-sans">

    <nav class="bg-gray-900 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center py-3 px-4">
            <div class="flex items-center gap-2 px-3 py-1 bg-gray-800/70 backdrop-blur-md rounded-xl shadow-sm hover:shadow-xl transition">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="h-8 w-8">
                <span class="text-xl font-semibold text-white tracking-wide">Riki Vapor</span>
            </div>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="px-3 py-2 rounded transition duration-300 hover:shadow-lg hover:shadow-red-500/40">Home</a>
                <a href="{{ url('/pembelian') }}" class="px-3 py-2 rounded transition duration-300 hover:shadow-lg hover:shadow-emerald-500/40">Pembelian</a>
                <a href="{{ url('/tentang') }}" class="px-3 py-2 rounded transition duration-300 hover:shadow-lg hover:shadow-indigo-400/40">Tentang Kami</a>
                <a href="{{ url('/kontak') }}" class="px-3 py-2 rounded transition duration-300 hover:shadow-lg hover:shadow-blue-500/40">Kontak</a>
            </div>
        </div>
    </nav>

    <main class="p-8 container mx-auto">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white mt-20 pt-10 pb-6">
        <div class="container mx-auto px-6 md:flex md:justify-between md:gap-10">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo Riki Vapor" class="h-12 w-12">
                    <h2 class="text-2xl font-bold">Riki Vapor</h2>
                </div>
                <p class="text-sm leading-relaxed">
                    Riki Vapor adalah tempat pilihan vapers dari pemula hingga profesional.
                    Kami hadir dengan produk berkualitas dan pengalaman terbaik di dunia vaping.
                </p>
                <div class="flex space-x-4 mt-4 text-3xl">
                    <a href="#" class="text-white hover:text-red-500 transition duration-300"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-white hover:text-rose-400 transition duration-300"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white hover:text-blue-500 transition duration-300"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white hover:text-green-500 transition duration-300"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="mb-10 md:mb-0 md:w-1/5">
                <h3 class="text-lg font-semibold mb-3"><span class="text-red-400 font-medium">Navigasi</span></h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ url('/pembelian') }}" class="hover:underline">Pembelian</a></li>
                    <li><a href="{{ url('/tentang') }}" class="hover:underline">Tentang Kami</a></li>
                    <li><a href="{{ url('/kontak') }}" class="hover:underline">Kontak</a></li>
                </ul>
            </div>
            <div class="md:w-1/2">
                <h3 class="text-lg font-semibold mb-3"><span class="text-red-400 font-medium">Lokasi Kami</span></h3>
                <iframe
                    src="https://www.google.com/maps/embed/v1/place?q=https://www.google.com/maps/place/Riki+vapor/@0.480716,101.3288945,14z/data=!4m6!3m5!1s0x31d5a9ae4b938a15:0x5e04d595b66a1a0a!8m2!3d0.4807133!4d101.3649437!16s%2Fg%2F11n11lk5vd?entry=ttu&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
        <div class="text-center text-sm text-gray-400 mt-10 border-t border-gray-700 pt-4">
            <span class="text-red-400 font-medium">© Riki Vapor</span> {{ date('Y') }}. All rights reserved. | Design by <span class="text-red-400 font-medium">Fajar</span>
        </div>
    </footer>

    {{-- Floating Cart Button (FAB) --}}
    @if(Request::is('pembelian') || Request::is('keranjang'))
    <a href="/keranjang" x-data="{ cartCount: Number(localStorage.getItem('cartCount') || 0) }"
       @cart-updated.window="cartCount = $event.detail.cartCount;"
       x-show="true"
       class="fixed bottom-8 right-8 z-50 transform hover:scale-110 transition-transform duration-200 bg-transparent"
       title="Keranjang Belanja"
    >
        <i class="fas fa-shopping-cart text-4xl text-green-500"></i>
        <span x-show="cartCount > 0"
              class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center border-2 border-gray-900">
            <span x-text="cartCount"></span>
        </span>
    </a>
    @endif

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
</body>
</html>