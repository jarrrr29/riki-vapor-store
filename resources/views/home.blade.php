@extends('layouts.app')

@section('content')
<div class="text-center mt-10">
    <h1 class="text-4xl font-bold mb-4">Selamat Datang di Riki Vapor</h1>
    <p class="text-gray-300 mb-6">Toko vape terpercaya dengan produk berkualitas tinggi.</p>
    <a href="{{ url('/pembelian') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full">Lihat Produk</a>
</div>
<section class="mt-20 bg-gray-800 rounded-lg p-8">
    <h2 class="text-2xl font-bold mb-4 text-white">Kenapa Memilih Kami?</h2>
    <ul class="text-gray-300 space-y-2 list-disc list-inside">
        <li>Produk 100% Original & Terpercaya</li>
        <li>Harga Bersahabat dan Banyak Promo</li>
        <li>Pilihan Rasa dan Tipe Vape Lengkap</li>
        <li>Pengiriman Cepat dan Aman</li>
    </ul>
</section>
@endsection