@extends('layouts.app')

@section('content')
    <section class="py-12 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-white">Kontak Kami</h1>

        <p class="text-gray-300 mb-4">
            Jika Anda memiliki pertanyaan, saran, atau ingin memesan produk, silakan hubungi kami melalui informasi di bawah ini.
        </p>

        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <ul class="text-gray-300 space-y-4">
                <li>
                    <strong>Alamat Toko:</strong><br>
                    Jl. Vape Sehat No. 88, Jakarta Selatan, Indonesia
                </li>
                <li>
                    <strong>WhatsApp:</strong><br>
                    <a href="https://wa.me/6281234567890" class="text-green-400 hover:underline" target="_blank">
                        +62 812-3456-7890
                    </a>
                </li>
                <li>
                    <strong>Email:</strong><br>
                    <a href="mailto:rikivapor@gmail.com" class="text-green-400 hover:underline">
                        rikivapor@gmail.com
                    </a>
                </li>
                <li>
                    <strong>Jam Operasional:</strong><br>
                    Senin – Sabtu, 10:00 – 20:00 WIB
                </li>
            </ul>
        </div>
    </section>
@endsection
