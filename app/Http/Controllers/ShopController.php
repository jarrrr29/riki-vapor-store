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

        // Ambil jumlah total kuantitas item di keranjang dari session
        $initialCartCount = array_sum(array_column(session()->get('cart', []), 'quantity'));

        // Kirim data yang sudah dikelompokkan dan jumlah keranjang ke view 'shop'
        return view('shop', compact('kategoriProduk', 'initialCartCount'));
    }
}