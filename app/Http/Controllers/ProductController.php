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
}
