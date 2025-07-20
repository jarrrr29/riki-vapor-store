@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-white"> {{-- Ganti max-w-4xl menjadi max-w-7xl agar lebih lebar --}}

    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-wider uppercase">Hubungi & Ulasan</h1>
        <p class="text-gray-400 mt-2">Kami siap membantu dan mendengar dari Anda.</p>
    </div>

    {{-- Container 3 Kolom --}}
    <div class="grid md:grid-cols-3 gap-8">

        <div class="bg-gray-800 rounded-lg shadow-2xl p-8 space-y-6">
            <h2 class="text-2xl font-bold text-green-400 mb-4">Informasi Kontak</h2>
            
            <div class="flex items-start space-x-4">
                <i class="fas fa-map-marker-alt text-2xl text-green-500 mt-1"></i>
                <div>
                    <h3 class="font-semibold">Alamat Toko</h3>
                    <p class="text-gray-400">Jl. Garuda Sakti Km 2, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293</p>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <i class="fab fa-whatsapp text-2xl text-green-500 mt-1"></i>
                <div>
                    <h3 class="font-semibold">WhatsApp</h3>
                    <a href="https://wa.me/6285272830575" target="_blank" class="text-gray-400 hover:text-green-400 transition">+62 852-7283-0575</a>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <i class="fas fa-envelope text-2xl text-green-500 mt-1"></i>
                <div>
                    <h3 class="font-semibold">Email</h3>
                    <a href="mailto:rikivapor@gmail.com" class="text-gray-400 hover:text-green-400 transition">rikivapor@gmail.com</a>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <i class="fas fa-clock text-2xl text-green-500 mt-1"></i>
                <div>
                    <h3 class="font-semibold">Jam Operasional</h3>
                    <p class="text-gray-400">Senin – Sabtu, 10:00 – 22:00 WIB</p>
                    <p class="text-gray-400">Minggu, 13:00 – 22:00 WIB</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-green-400 mb-4">Lokasi Kami</h2>
            <div class="rounded-lg overflow-hidden h-96">
                {{-- Pastikan URL peta Anda sudah benar --}}
                <iframe 
                    src="https://www.google.com/maps/embed/v1/place?q=https://www.google.com/maps/place/Riki+vapor/@0.480716,101.3288945,14z/data=!4m6!3m5!1s0x31d5a9ae4b938a15:0x5e04d595b66a1a0a!8m2!3d0.4807133!4d101.3649437!16s%2Fg%2F11n11lk5vd?entry=ttu&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-2xl p-8 text-gray-800">
             <h2 class="text-2xl font-bold text-green-600 mb-4">Ulasan Pelanggan</h2>
            <div id="shapo-widget-b585c905eadbdef13a50"></div>
            <script id="shapo-embed-js" type="text/javascript" src="https://cdn.shapo.io/js/embed.js" defer></script>
        </div>

    </div>
</div>
@endsection