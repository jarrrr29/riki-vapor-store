<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController; // Pastikan ini ada
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk halaman utama (Home)
Route::get('/', [HomeController::class, 'index']);

// Rute untuk halaman Pembelian (Katalog Produk)
Route::get('/pembelian', [ShopController::class, 'index']);

// Rute untuk halaman Tentang Kami
Route::get('/tentang', function () {
    return view('about');
});

// Rute untuk halaman Kontak
Route::get('/kontak', function () {
    return view('contact');
});

// Route untuk menambah produk ke keranjang (via AJAX)
Route::post('/keranjang/tambah/{productId}', [CartController::class, 'tambah'])->name('keranjang.tambah');

// Route untuk menampilkan halaman keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');

// Route untuk memperbarui kuantitas produk di keranjang (via AJAX)
Route::post('/keranjang/update/{productId}', [CartController::class, 'updateQuantity'])->name('keranjang.update');

// Route untuk menghapus produk dari keranjang (via AJAX)
Route::post('/keranjang/remove/{productId}', [CartController::class, 'remove'])->name('keranjang.remove');

// --- ROUTE SEMENTARA UNTUK MENAMBAHKAN PRODUK BARU (DIKOMENTARI) ---
// Aktifkan route ini HANYA JIKA Anda perlu menambahkan data produk baru lagi.
// Setelah selesai, segera komentari kembali atau hapus.
/*
Route::get('/add-new-products', function () {
    // Pastikan Product Model ter-import di atas: use App\Models\Product;
    // Ini akan menambahkan semua produk dari daftar yang Anda berikan
    
    // Produk yang sudah ada (jika belum ada di database, uncomment dan jalankan sekali)
    // \App\Models\Product::create([
    //     'nama' => 'Vape Sakura 5000 Puff',
    //     'deskripsi' => 'Rasa buah segar, cocok untuk pemula.',
    //     'harga' => 150000,
    //     'gambar' => 'pod.jpg',
    //     'kategori' => 'Pod'
    // ]);
    // \App\Models\Product::create([
    //     'nama' => 'Pod Infinity X',
    //     'deskripsi' => 'Nikotin halus dan aroma kuat.',
    //     'harga' => 220000,
    //     'gambar' => 'pod.jpg',
    //     'kategori' => 'Pod'
    // ]);


    // Produk-produk baru yang Anda berikan gambarnya (uncomment dan jalankan sekali jika belum ada)
    // \App\Models\Product::create([
    //     'nama' => 'The O-Rama Juice Strawberry Cheesecake',
    //     'deskripsi' => 'Freebase 60ml / 3-6mg, Pods Friendly 30ml / 15mg. Rasa Strawberry Cheesecake.',
    //     'harga' => 175000, // Harga contoh
    //     'gambar' => 'liquid10.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'PODA Juice - Show Must Go On',
    //     'deskripsi' => 'E-Liquid company by PODA. Konsep musik rock.',
    //     'harga' => 160000, // Harga contoh
    //     'gambar' => 'liquid9.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'Parfait Strawberry Parfait Salt',
    //     'deskripsi' => 'Rasa Strawberry Parfait dengan Salt Nic 25mg.',
    //     'harga' => 90000, // Harga contoh
    //     'gambar' => 'liquid6.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'Ice Cream Series - Rainpop Pelangi',
    //     'deskripsi' => 'E-liquid Ice Cream Series Rainpop Pelangi. Nicotin 3mg/6mg.',
    //     'harga' => 110000, // Harga contoh
    //     'gambar' => 'liquid3.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'Jinak Pods - Salt Series (Pilihan Rasa)',
    //     'deskripsi' => 'Saltnic Pods dengan varian rasa Liang Teh, Strawberry Ice, Es Doger, Es Cendol, Taro Ice.',
    //     'harga' => 85000, // Harga contoh
    //     'gambar' => 'liquid4.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'SWRL - Blueberry Vanilla Cream',
    //     'deskripsi' => 'Liquid creamy Blueberry Vanilla Cream 60ml.',
    //     'harga' => 140000, // Harga contoh
    //     'gambar' => 'liquid5.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'Hexjuice CASH Series',
    //     'deskripsi' => 'Liquid Freebase 3mg dengan varian rasa Lebbul Salted Caramel, Sabino Fried Cookies, Sonoran Strawberry.',
    //     'harga' => 150000, // Harga contoh
    //     'gambar' => 'liquid7.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'HEXOHM V3 Anodized',
    //     'deskripsi' => 'HEXOHM V3 Anodized mod. Pilihan warna hijau, merah, biru, ungu, hitam. Garansi Lifetime.',
    //     'harga' => 950000, // Harga contoh
    //     'gambar' => 'pod2.jpg',
    //     'kategori' => 'Pod'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'Lunar Donut Blueberry Jam',
    //     'deskripsi' => 'E-juice Blueberry Jam dengan tema Donat.',
    //     'harga' => 130000, // Harga contoh
    //     'gambar' => 'liquid2.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // \App\Models\Product::create([
    //     'nama' => 'Candyman Fruity Series',
    //     'deskripsi' => 'Liquid Freebase 60ml / 3mg dan Saltnic 30ml / 30mg dengan varian rasa fruity.',
    //     'harga' => 120000, // Harga contoh
    //     'gambar' => 'liquid8.jpg',
    //     'kategori' => 'Liquid'
    // ]);

    // return "Produk baru berhasil ditambahkan!";
});
*/
// --- AKHIR ROUTE SEMENTARA ---

// Jika Anda menginstal Laravel Breeze, rute-rute autentikasi akan ada di sini
// require __DIR__.'/auth.php';