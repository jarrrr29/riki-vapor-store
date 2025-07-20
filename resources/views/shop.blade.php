@extends('layouts.app')

@section('content')

{{-- Judul Halaman yang Menonjol --}}
<div class="text-center mb-10">
    <h1 class="text-4xl font-extrabold text-white uppercase tracking-wider mb-4">
        Katalog Produk Riki Vapor
    </h1>
    <div class="flex items-center justify-center space-x-3">
        <hr class="w-16 border-t-2 border-green-500">
        <span class="text-green-500 text-xl">★★★★★</span> {{-- Bisa ganti ikon lain --}}
        <hr class="w-16 border-t-2 border-green-500">
    </div>
    {{-- Pesan feedback dari addToCart atau order --}}
    <p x-text="message" x-show="message" x-transition class="mt-4 text-green-500 font-semibold"></p>
</div>

{{-- Container utama Alpine.js untuk kategori filtering dan keranjang/pemesanan --}}
<div x-data="{
    kategori: 'all', // Default tampilkan semua kategori
    message: '', // Pesan feedback di halaman
    cartCount: Number(localStorage.getItem('cartCount') || 0), // Ambil dari localStorage untuk FAB
    
    // Data dan fungsi untuk Modal Pemesanan
    showOrderModal: false, // PASTI false secara default
    orderProductId: null,
    orderQuantity: 1,
    customerName: '',
    customerPhone: '',
    customerAddress: '',
    orderMessage: '', // Pesan di dalam modal

    // Fungsi untuk membuka modal pemesanan
    openOrderModal(productId, quantity) {
        this.orderProductId = productId;
        this.orderQuantity = quantity;
        this.orderMessage = ''; // Reset pesan modal
        this.customerName = ''; // Reset form
        this.customerPhone = '';
        this.customerAddress = '';
        this.showOrderModal = true;
    },

    // Fungsi untuk mengirim pesanan dari modal
    submitOrder() {
        this.orderMessage = ''; // Reset pesan
        fetch(`/order/store`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: this.orderProductId,
                quantity: this.orderQuantity,
                customer_name: this.customerName,
                customer_phone: this.customerPhone,
                customer_address: this.customerAddress
            })
        })
        .then(response => response.json())
        .then(data => {
            this.orderMessage = data.message;
            if (data.success) {
                this.message = 'Pesanan Anda berhasil dibuat!'; // Tampilkan pesan di halaman utama
                // Reset keranjang (jika sebelumnya ada, karena ini alur pemesanan langsung)
                this.cartCount = 0; // Reset FAB count
                localStorage.setItem('cartCount', 0); // Reset localStorage
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: 0 } })); // Update FAB
                
                setTimeout(() => { this.showOrderModal = false; this.message = ''; }, 3000); // Tutup modal setelah 3 detik
            } else {
                console.error('Gagal membuat pesanan:', data.message);
            }
        })
        .catch(error => {
            console.error('Ada kesalahan:', error);
            this.orderMessage = 'Terjadi kesalahan saat memproses pesanan.';
        });
    },

    // Fungsi untuk menambah produk ke keranjang sesi (ini yang dipanggil tombol Chekout)
    addToCart: function(productId, qty) { // Menggunakan function() {} untuk kompatibilitas lebih luas
        this.message = ''; // Reset pesan feedback

        // Animasi pop (pemicu animasi tombol)
        let btn = event.target;
        btn.classList.add('animation-pulse-on-click');
        setTimeout(() => {
            btn.classList.remove('animation-pulse-on-click');
        }, 500);

        fetch(`/keranjang/tambah/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
            },
            body: JSON.stringify({ quantity: qty })
        })
        .then(response => response.json())
        .then(data => {
            this.message = data.message; // Pesan sukses/gagal dari backend
            if (data.success) {
                this.cartCount = data.cartCount; // Update cartCount dari respons backend
                localStorage.setItem('cartCount', data.cartCount); // Simpan ke localStorage untuk FAB
                console.log('Produk diChekoutkan:', data.message);
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: data.cartCount } })); // Emit event ke FAB
            } else {
                console.error('Gagal menambahkan produk:', data.message);
            }
        })
        .catch(error => {
            console.error('Ada kesalahan:', error);
            this.message = 'Terjadi kesalahan saat menambah produk.';
        });
    }
}"
x-init="
    // Pastikan cartCount di Alpine.js selalu sinkron dengan localStorage saat halaman dimuat
    cartCount = Number(localStorage.getItem('cartCount') || 0);
    // Emit event untuk update FAB di app.blade.php dengan nilai awal dari localStorage
    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: cartCount } }));
"
class="mb-8">

    {{-- CAROUSEL PRODUK UNGGULAN UTAMA (SPLIDE.JS - GAMBAR KIRI, DETAIL KANAN) --}}
    <div id="main-splide" class="splide relative w-full mx-auto max-w-4xl mb-6" aria-label="Produk Unggulan Riki Vapor">
        <div class="splide__track rounded-lg shadow-xl border border-gray-700 bg-gray-900 p-6">
            <ul class="splide__list">
                

                 @foreach($featuredProducts as $product)
            <a href="{{ route('product.show', $product->id) }}" class="splide__slide block cursor-pointer group">
                <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-8">
                    {{-- Gambar Produk (Kiri) --}}
                    <div class="w-full md:w-1/2 h-64 md:h-80 bg-black rounded-lg flex items-center justify-center overflow-hidden shadow-inner mb-4 md:mb-0">
                        <img src="{{ asset('image/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-contain p-2 transform group-hover:scale-110 transition-transform duration-300">
                    </div>
                    
                    {{-- Detail Produk (Kanan) --}}
                    <div class="w-full md:w-1/2 text-center md:text-left p-2">
                        <span class="text-green-400 text-sm font-semibold uppercase mb-1 block">{{ $product->kategori }}</span>
                        <h3 class="text-3xl font-extrabold text-white mb-2 group-hover:text-green-400 transition">{{ $product->nama }}</h3>
                        <p class="text-gray-300 text-md mb-4 line-clamp-3">{{ $product->deskripsi }}</p>
                        <p class="text-green-400 font-extrabold text-3xl mb-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        <div class="mt-4 text-center md:text-left">
                            <span class="inline-block bg-gray-700 text-white px-5 py-2 rounded-full group-hover:bg-green-600 transition">
                                Lihat Detail &rarr;
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
            </ul>
        </div>
    </div>

    {{-- THUMBNAIL CAROUSEL (KUMPULAN ITEM LAIN DI BAWAH) --}}
    
    <div id="thumbnail-splide" class="splide relative w-full mx-auto max-w-4xl" aria-label="Thumbnail Produk Unggulan">
        <div class="splide__track">
            <ul class="splide__list">
                
            </ul>
        </div>
    </div>
{{-- REKOMENDASI & PRODUK TERLARIS --}}
<div class="mt-16 mb-12">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white">Paling Laris Minggu Ini</h2>
        <p class="text-gray-400">Produk yang paling banyak dicari oleh pelanggan kami.</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @php
            // Anda bisa mengganti ini dengan query ke database untuk produk terlaris
            $rekomendasiProduk = [
                ['id' => 8, 'nama' => 'HEXOHM V3 Anodized', 'harga' => 950000, 'gambar' => asset('image/pod2.jpg')],
                ['id' => 1, 'nama' => 'Vape Sakura 5000 Puff', 'harga' => 150000, 'gambar' => asset('image/pod.jpg')],
                ['id' => 6, 'nama' => 'SWRL - Blueberry Vanilla Cream', 'harga' => 140000, 'gambar' => asset('image/liquid5.jpg')],
                ['id' => 9, 'nama' => 'Lunar Donut Blueberry Jam', 'harga' => 130000, 'gambar' => asset('image/liquid2.jpg')]
            ];
        @endphp

        @foreach($rekomendasiProduk as $produk)
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
            <div class="relative">
                <img src="{{ $produk['gambar'] }}" alt="{{ $produk['nama'] }}" class="w-full h-40 object-cover">
                <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-md">Terlaris!</div>
            </div>
            <div class="p-4 text-center">
                <h3 class="font-semibold text-white h-12">{{ $produk['nama'] }}</h3>
                <p class="text-green-400 font-bold mt-2">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</p>
                <button @click="addToCart({{ $produk['id'] }}, 1)" class="mt-4 w-full bg-green-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-green-700 transition">
                    + Keranjang
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

    {{-- Nav Kategori --}}
    <div class="flex justify-center flex-wrap gap-4 mt-16 mb-8">
        <button
            @click="kategori = 'all'"
            class="px-6 py-2 rounded-full border text-sm font-semibold transition"
            :class="kategori === 'all' ? 'bg-green-600 text-white border-green-600' : 'bg-gray-700 text-white border-gray-600 hover:bg-gray-600'"
        >Semua</button>
        @foreach($kategoriProduk as $kategoriName => $produkList)
        <button
            @click="kategori = '{{ $kategoriName }}'"
            class="px-6 py-2 rounded-full border text-sm font-semibold transition"
            :class="kategori === '{{ $kategoriName }}' ? 'text-green-400 border border-green-400' : 'text-gray-400 hover:text-white'"
        >{{ ucfirst($kategoriName) }}</button>
        @endforeach
    </div>

    {{-- DAFTAR PRODUK PER KATEGORI --}}
    @foreach($kategoriProduk as $kategori => $produkList)
    <div id="{{ Str::slug($kategori) }}" x-show="kategori === 'all' || kategori === '{{ $kategori }}'" x-transition class="mb-10">
        {{-- JUDUL H2 KATEGORI --}}
        <div class="flex items-center mb-6">
            <h2 class="text-xl font-bold text-white mr-4">{{ ucfirst($kategori) }}</h2>
            <hr class="flex-grow border-t border-gray-700">
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($produkList as $item)
            <div x-data="{ localQuantity: 1 }" {{-- x-data lokal untuk kuantitas setiap card --}}
                 class="bg-gray-800 rounded-lg shadow-md overflow-hidden max-w-[180px] mx-auto border border-transparent hover:border-green-500 transition duration-300 transform hover:scale-105">
                <div class="relative w-full h-36 bg-gray-900 flex items-center justify-center rounded-t-lg">
                    <img
                        src="{{ asset('image/' . $item->gambar) }}"
                        alt="{{ $item->nama }}"
                        class="h-full object-cover w-full rounded-t-lg"
                    >
                </div>
                <div class="p-3 bg-gray-800 text-center">
                    <h3 class="text-sm font-semibold text-white mb-1 line-clamp-2 leading-tight">
                        {{ $item->nama }}
                    </h3>
                    <p class="text-green-400 font-bold text-sm mt-2">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center justify-center mt-3 space-x-1">
                        <button
                            type="button"
                            @click="localQuantity = Math.max(1, localQuantity - 1)"
                            class="bg-gray-700 text-white w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-600 transition"
                        >-
                        </button>
                        <input
                            type="number"
                            min="1"
                            x-model="localQuantity"
                            class="w-10 h-7 p-1 text-center bg-gray-900 text-green-400 rounded-md focus:outline-none text-sm font-bold no-spinner"
                        >
                        <button
                            type="button"
                            @click="localQuantity++"
                            class="bg-gray-700 text-white w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-600 transition"
                        >+
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="addToCart({{ Js::from($item->id) }}, localQuantity)" {{-- Menggunakan Js::from untuk keamanan --}}
                        class="mt-3 bg-green-600 text-white text-xs px-2 py-1 rounded-md hover:bg-green-700 transition duration-200 w-full animation-pulse-on-click"
                    >
                        Chekout
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

{{-- MODAL FORM PEMESANAN (DIHAPUS DARI SINI, AKAN DIPINDAHKAN KE HALAMAN KERANJANG) --}}
@endsection

{{-- Script untuk menginisialisasi Splide.js --}}
@push('scripts')
<script>
    // Keyframes untuk animasi tombol
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes pulse-on-click {
            0% { transform: scale(1); background-color: #34D399; }
            50% { transform: scale(1.05); background-color: #10B981; }
            100% { transform: scale(1); background-color: #059669; }
        }
        .animation-pulse-on-click {
            animation: pulse-on-click 0.3s ease-in-out;
        }
        /* CSS untuk menghilangkan tombol spinner di input type="number" */
        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .no-spinner {
            -moz-appearance: textfield; /* Firefox */
        }
    `;
    document.head.appendChild(style);

    document.addEventListener('DOMContentLoaded', function () {
        var mainSplide = new Splide('#main-splide', {
            type: 'loop',
            perPage: 1,
            autoplay: true, // Auto-play diaktifkan kembali
            interval: 3000, // Ganti setiap 3 detik
            arrows: true,
            pagination: false, // Hanya panah, tanpa pagination dots
            breakpoints: {
                768: { // Untuk tablet dan mobile
                    arrows: false, // Sembunyikan panah di mobile jika terlalu kecil
                },
            },
        });

        var thumbnailSplide = new Splide('#thumbnail-splide', {
            rewind: true, // Kembali ke awal setelah slide terakhir
            fixedWidth: 100, // Lebar tetap untuk setiap thumbnail
            fixedHeight: 100, // Tinggi tetap untuk setiap thumbnail
            isNavigation: true, // Ini adalah slider navigasi
            gap: 10, // Jarak antar thumbnail
            focus: 'center', // Thumbnail aktif akan di tengah
            pagination: false, // Tidak perlu pagination dots
            arrows: false, // Panah tidak perlu di thumbnail
            breakpoints: {
                768: {
                    fixedWidth: 80,
                    fixedHeight: 80,
                    gap: 5,
                    perPage: 3, // Tampilkan 3 thumbnail di mobile
                },
            },
        });

        // Sinkronkan dua slider
        mainSplide.sync(thumbnailSplide);

        // Pasang slider setelah sinkronisasi
        mainSplide.mount();
        thumbnailSplide.mount();

        // Pastikan tombol reset animasi setelah klik
        document.querySelectorAll('.animation-pulse-on-click').forEach(button => {
            button.addEventListener('animationend', () => {
                button.classList.remove('animation-pulse-on-click');
            });
        });
    });
</script>
@endpush
