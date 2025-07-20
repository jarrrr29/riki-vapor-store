@extends('layouts.app')

@section('content')
<style>
    /* Style untuk hero section */
    .smoke-graphic {
        position: absolute;
        bottom: 0;
        left: -5%;
        width: 70%;
        max-width: 600px;
        opacity: 0.8;
        z-index: 1;
        pointer-events: none;
    }
    .content-container {
        position: relative;
        z-index: 2;
    }

    /* ▼▼▼ CSS BARU UNTUK CAROUSEL GALERI ANDA ▼▼▼ */
    .gallery-container {
        width: 100%;
        height: 70vh; /* Tinggi carousel */
        max-height: 550px;
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        box-shadow: 0 30px 50px rgba(0,0,0,0.4);
    }
    #slide {
        width: max-content;
        height: 100%;
    }
    .item {
        width: 150px;
        height: 250px;
        display: inline-block;
        transition: 0.5s;
        position: absolute;
        background-position: center;
        background-size: cover;
        z-index: 1;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    .item:nth-child(1),
    .item:nth-child(2) {
        left: 0;
        top: 0;
        transform: translate(0, 0);
        border-radius: 0;
        width: 100%;
        height: 100%;
        box-shadow: none;
    }
    .item:nth-child(3) { left: 75%; }
    .item:nth-child(4) { left: calc(75% + 170px); }
    .item:nth-child(n + 5) {
        left: calc(75% + 340px);
        opacity: 0;
    }
    .item .content {
        position: absolute;
        top: 50%;
        left: 100px;
        width: 40%;
        max-width: 400px;
        text-align: left;
        color: #eee;
        transform: translateY(-50%);
        display: none;
        text-shadow: 0 1px 5px rgba(0,0,0,0.6);
    }
    .item:nth-child(2) .content {
        display: block;
        z-index: 111;
    }
    .item .name {
        color: #34D399; /* Warna hijau tema */
        font-size: 2.5rem;
        font-weight: bold;
        opacity: 0;
        animation: showcontent 1s ease-in-out 1 forwards;
    }
    .item .des {
        margin: 20px 0;
        color: white;
        opacity: 0;
        animation: showcontent 1s ease-in-out 0.3s 1 forwards;
    }
    @keyframes showcontent {
        from {
            opacity: 0;
            transform: translateY(50px);
            filter: blur(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }
    }
    .gallery-buttons {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 222;
        width: 90%;
        max-width: 350px;
        display: flex;
        justify-content: space-between;
    }
    .gallery-buttons button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        cursor: pointer;
        transition: 0.5s;
    }
    .gallery-buttons button:hover {
        background-color: #34D399;
    }
    /* ▲▲▲ CSS BARU UNTUK CAROUSEL GALERI ANDA ▲▲▲ */
</style>

{{-- SEKSI 2: CAROUSEL GALERI GAYA BARU --}}
<div class="py-12">
    <div class="container mx-auto">
        <div class="gallery-container">
            <div id="slide">
                <div class="item" style="background-image: url('{{ asset('image/galeri-toko-1.png') }}');">
                    <div class="content">
                        <div class="name">KOLEKSI LENGKAP</div>
                        <div class="des">Kami menyediakan ratusan pilihan liquid dan device otentik untuk memenuhi selera Anda.</div>
                    </div>
                </div>
                <div class="item" style="background-image: url('{{ asset('image/galeri-produk-1.png') }}');">
                    <div class="content">
                        <div class="name">DEVICE TERBAIK</div>
                        <div class="des">Temukan mods, pods, dan starter kit dari brand-brand terbaik dunia di toko kami.</div>
                    </div>
                </div>
                <div class="item" style="background-image: url('{{ asset('image/galeri-komunitas-1.jpg') }}');">
                    <div class="content">
                        <div class="name">KOMUNITAS VAPERS</div>
                        <div class="des">Riki Vapor bukan hanya toko, tapi tempat berkumpul dan berbagi cerita sesama vapers.</div>
                    </div>
                </div>
                <div class="item" style="background-image: url('{{ asset('image/galeri-liquid-1.png') }}');">
                    <div class="content">
                        <div class="name">RASA JUARA</div>
                        <div class="des">Dari fruity yang segar hingga creamy yang legit, semua rasa liquid favoritmu ada di sini.</div>
                    </div>
                </div>
                <div class="item" style="background-image: url('{{ asset('image/galeri-toko-2.jpg') }}');">
                    <div class="content">
                        <div class="name">SUASANA NYAMAN</div>
                        <div class="des">Nikmati suasana toko yang nyaman dan pelayanan ramah dari tim kami.</div>
                    </div>
                </div>
            </div>
            <div class="gallery-buttons">
                <button id="prev"><i class="fas fa-arrow-left"></i></button>
                <button id="next"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

{{-- SEKSI 1: HERO SECTION DENGAN MASKOT --}}
<div class="relative min-h-[70vh] flex items-center">
    <img src="{{ asset('image/smoke-graphic.gif') }}" alt="Smoke Graphic" class="smoke-graphic">
    <div class="container mx-auto content-container">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="text-center md:text-left">
                <img src="https://images.cooltext.com/5735757.png" alt="Selamat Datang di Dunia Riki Vapor" class="w-full max-w-4x2 mx-auto md:mx-0">
                <p class="text-gray-300 mt-4 text-lg max-w-lg mx-auto md:mx-0">
                    Pusat kebutuhan vaping terlengkap di Pekanbaru. Kami hadir untuk memberikan pengalaman terbaik bagi para vapers, dari pemula hingga profesional.
                </p>
            </div>
            <div class="text-center">
                <img src="{{ asset('image/vape-mascot.gif') }}" alt="Riki Vapor Mascot" class="w-80 mx-auto mb-4">
                <p class="text-lg italic text-green-400 mb-6">"Kualitas Terbaik, Rasa Juara."</p>
                <a href="{{ url('/pembelian') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-lg font-semibold px-8 py-3 rounded-full transition transform hover:scale-105 shadow-lg shadow-green-500/30">
                    <i class="fas fa-rocket"></i>
                    <span>Jelajahi Produk</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SEKSI 3: KENAPA HARUS PILIH KAMI --}}
<div class="py-12 bg-gray-800">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold text-white mb-2">Kenapa Harus Riki Vapor?</h2>
        <p class="text-gray-400 mb-10">Kami bukan sekadar toko, kami adalah partner vaping Anda.</p>
        <div class="grid md:grid-cols-3 gap-10">
            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 transform hover:-translate-y-2 transition-transform duration-300">
                <div class="text-4xl text-green-500 mb-4"><i class="fas fa-certificate"></i></div>
                <h3 class="text-xl font-semibold text-white mb-2">Produk 100% Otentik</h3>
                <p class="text-gray-400 text-sm">Kami hanya menjual produk asli dari brand-brand terpercaya. Kualitas dan kepuasan Anda adalah prioritas kami.</p>
            </div>
            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 transform hover:-translate-y-2 transition-transform duration-300">
                <div class="text-4xl text-green-500 mb-4"><i class="fas fa-users"></i></div>
                <h3 class="text-xl font-semibold text-white mb-2">Partner Komunitas</h3>
                <p class="text-gray-400 text-sm">Kami siap menjadi teman diskusi Anda, memberikan rekomendasi jujur untuk menemukan rasa dan device yang paling pas.</p>
            </div>
            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 transform hover:-translate-y-2 transition-transform duration-300">
                <div class="text-4xl text-green-500 mb-4"><i class="fas fa-tags"></i></div>
                <h3 class="text-xl font-semibold text-white mb-2">Harga Jujur & Promo</h3>
                <p class="text-gray-400 text-sm">Nikmati kualitas terbaik dengan harga yang bersahabat. Selalu ada promo menarik menanti Anda di Riki Vapor.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Script untuk Carousel Galeri Baru
        const nextButton = document.getElementById('next');
        const prevButton = document.getElementById('prev');
        const slide = document.getElementById('slide');

        if (nextButton && prevButton && slide) {
            nextButton.onclick = function(){
                let items = slide.querySelectorAll('.item');
                slide.appendChild(items[0]);
            }

            prevButton.onclick = function(){
                let items = slide.querySelectorAll('.item');
                slide.prepend(items[items.length - 1]);
            }
            setInterval(function() {
                nextButton.click();
            }, 2500); // 2500 milidetik = 2,5 detik
        }
    });
</script>
@endpush