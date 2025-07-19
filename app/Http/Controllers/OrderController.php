<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Import model Order
use App\Models\Product; // Import model Product

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
        ]);

        // Ambil detail produk untuk menghitung total harga
        $product = Product::find($validatedData['product_id']);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        $totalPrice = $product->harga * $validatedData['quantity'];

        // Simpan pesanan ke database
        try {
            Order::create([
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'customer_name' => $validatedData['customer_name'],
                'customer_phone' => $validatedData['customer_phone'],
                'customer_address' => $validatedData['customer_address'],
                'total_price' => $totalPrice,
            ]);

            // Reset keranjang sesi (jika ada, karena sekarang langsung pesan)
            session()->forget('cart'); // Hapus semua item dari sesi keranjang

            // Beri respons sukses
            return response()->json(['success' => true, 'message' => 'Pesanan berhasil dibuat!'], 200);

        } catch (\Exception $e) {
            // Tangani error database atau lainnya
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan pesanan.', 'error' => $e->getMessage()], 500);
        }
    }
}