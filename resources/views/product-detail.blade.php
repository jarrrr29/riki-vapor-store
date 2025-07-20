@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10" x-data="{ 
    mainImage: '{{ asset('image/' . $product->gambar) }}',
    quantity: 1,
    message: '',
    addToCart(productId, qty) {
        this.message = '';
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
            this.message = data.message;
            if (data.success) {
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: data.cartCount } }));
                localStorage.setItem('cartCount', data.cartCount);
                
                // Tampilkan notifikasi lalu hilangkan setelah 3 detik
                setTimeout(() => { this.message = '' }, 3000);
            }
        });
    }
}">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <div class="space-y-4">
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <img :src="mainImage" alt="{{ $product->nama }}" class="w-full h-96 object-contain p-4">
            </div>
            <div class="flex space-x-2">
                {{-- Contoh jika ada gambar lain, bisa di-loop dari database nanti --}}
                <div class="w-24 h-24 bg-gray-800 rounded-lg p-1 cursor-pointer" @click="mainImage = '{{ asset('image/' . $product->gambar) }}'">
                    <img src="{{ asset('image/' . $product->gambar) }}" alt="Thumbnail" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        <div class="text-white">
            <span class="text-sm uppercase text-green-400 font-semibold">{{ $product->kategori }}</span>
            <h1 class="text-4xl font-extrabold my-2">{{ $product->nama }}</h1>
            <p class="text-gray-300 mb-6">{{ $product->deskripsi }}</p>
            
            <div class="text-5xl font-bold text-green-400 mb-8">
                Rp {{ number_format($product->harga, 0, ',', '.') }}
            </div>

            <div class="flex items-center space-x-4 mb-6">
                <div class="flex items-center justify-center space-x-2">
                    <button @click="quantity = Math.max(1, quantity - 1)" class="bg-gray-700 text-white w-10 h-10 text-xl rounded-md hover:bg-gray-600 transition">-</button>
                    <input type="number" min="1" x-model="quantity" class="w-16 h-10 text-center bg-gray-800 text-white rounded-md focus:outline-none no-spinner text-lg">
                    <button @click="quantity++" class="bg-gray-700 text-white w-10 h-10 text-xl rounded-md hover:bg-gray-600 transition">+</button>
                </div>
                <button @click="addToCart({{ $product->id }}, quantity)" class="w-full bg-green-600 text-white text-lg font-semibold px-6 py-2 rounded-lg hover:bg-green-700 transition transform hover:scale-105">
                    + Tambah ke Keranjang
                </button>
            </div>
             <p x-text="message" x-show="message" class="text-green-500 font-semibold transition-opacity"></p>

        </div>
    </div>

    <div class="mt-24">
        <h2 class="text-2xl font-bold text-white mb-6 border-b-2 border-gray-700 pb-2">Produk Lainnya</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <a href="{{ route('product.show', $related->id) }}" class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 group">
                <div class="relative">
                    <img src="{{ asset('image/' . $related->gambar) }}" alt="{{ $related->nama }}" class="w-full h-40 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-white group-hover:text-green-400 transition">{{ $related->nama }}</h3>
                    <p class="text-green-500 font-bold mt-2">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .no-spinner {
        -moz-appearance: textfield;
    }
</style>
@endpush