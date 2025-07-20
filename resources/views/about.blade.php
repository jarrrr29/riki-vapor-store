@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto text-white">

    <div class="relative rounded-lg overflow-hidden mb-12 shadow-2xl">
        <img src="{{ asset('image/toko-anda.jpg') }}" alt="Suasana Toko Riki Vapor" class="w-full h-64 object-cover">
        <div class="absolute inset-0 bg-opacity-60 flex items-center justify-center p-8">
            <h1 class="text-4xl md:text-5xl font-extrabold text-center tracking-wider uppercase">
                Kisah di Balik Riki Vapor
            </h1>
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-center gap-10 mb-16">
        <div class="w-full md:w-1/3 flex-shrink-0">
            {{-- Pastikan nama file ini sesuai dengan foto no-background Anda --}}
            <img src="{{ asset('image/foto-riki.png') }}" alt="Riki - Pemilik Riki Vapor" class="max-w-xs mx-auto">
        </div>
        
        <div class="flex-grow text-center md:text-left">
            <h2 class="text-3xl font-bold mb-4 text-green-400">Selamat Datang, Vapers!</h2>
            <p class="text-gray-300 leading-relaxed">
                Halo, saya Riki. Sejak pertama kali mengenal dunia vape, saya sadar ini bukan sekadar alternatif, tapi sebuah komunitas dan gaya hidup. Riki Vapor lahir dari mimpi sederhana: menciptakan satu tempat terpercaya bagi para vapers di Pekanbaru, Riau, untuk mendapatkan produk terbaik, saran jujur, dan pelayanan yang ramah.
            </p>
            <p class="text-gray-300 leading-relaxed mt-3">
                Bagi kami, setiap pelanggan adalah teman. Kami tidak hanya menjual produk, kami berbagi pengalaman dan semangat yang sama.
            </p>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-8">
        <h2 class="text-3xl font-bold text-center mb-8">Filosofi Kami</h2>
        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-5xl text-green-500 mb-3">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Kualitas Terjamin</h3>
                <p class="text-gray-400 text-sm">
                    Setiap produk yang kami tawarkan, mulai dari device hingga liquid, adalah produk otentik yang telah kami seleksi kualitasnya.
                </p>
            </div>
            <div>
                <div class="text-5xl text-green-500 mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Komunitas & Pelayanan</h3>
                <p class="text-gray-400 text-sm">
                    Kami siap menjadi teman diskusi Anda, memberikan rekomendasi terbaik sesuai selera dan kebutuhan Anda.
                </p>
            </div>
            <div>
                <div class="text-5xl text-green-500 mb-3">
                    <i class="fas fa-tags"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Harga Kompetitif</h3>
                <p class="text-gray-400 text-sm">
                    Kami percaya kualitas tidak harus mahal. Nikmati produk-produk terbaik dengan harga yang bersahabat di kantong.
                </p>
            </div>
        </div>
    </div>

    <div class="text-center mt-16">
        <h2 class="text-2xl font-bold mb-4">Siap Menemukan Pengalaman Vaping Terbaik Anda?</h2>
        <p class="text-gray-400 mb-6 max-w-2xl mx-auto">
            Jelajahi koleksi kami dan temukan apa yang Anda cari. Jika ada pertanyaan, jangan ragu untuk menghubungi kami.
        </p>
        <a href="{{ url('/pembelian') }}" class="bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-8 py-3 rounded-full transition transform hover:scale-105 shadow-lg">
            Lihat Katalog Produk
        </a>
    </div>

</div>
@endsection