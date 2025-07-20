<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $produk = Product::all();
        return view('shop', compact('produk'));
    }
     public function show($id)
    {
        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id);
        
        // Ambil produk lain secara acak untuk ditampilkan sebagai rekomendasi
        $relatedProducts = Product::where('id', '!=', $id) // Ambil produk selain yang sedang dilihat
                                  ->inRandomOrder()       // Urutkan secara acak
                                  ->limit(4)              // Batasi hanya 4 produk
                                  ->get();

        // Kirim data produk dan produk terkait ke view
        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
