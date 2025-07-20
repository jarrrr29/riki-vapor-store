<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
     public function index()
    {
        $produk = Product::all();
        $kategoriProduk = $produk->groupBy('kategori');

        // AMBIL 5 PRODUK SECARA ACAK UNTUK DITAMPILKAN DI CAROUSEL
        $featuredProducts = Product::inRandomOrder()->limit(5)->get();

        $initialCartCount = array_sum(array_column(session()->get('cart', []), 'quantity'));

        // KIRIM DATA featuredProducts KE VIEW
        return view('shop', compact('kategoriProduk', 'initialCartCount', 'featuredProducts'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id); // Cari produk berdasarkan ID, jika tidak ketemu akan error 404
        
        // Ambil produk lain secara acak sebagai rekomendasi, kecuali produk yang sedang dilihat
        $relatedProducts = Product::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}