<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data pelanggan
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'items' => 'required|array|min:1', // Pastikan ada item yang dipesan
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Loop melalui setiap item yang ada di keranjang
            foreach ($validatedData['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $totalPrice = $product->harga * $item['quantity'];

                    // Simpan setiap item sebagai satu baris di tabel 'orders'
                    Order::create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'customer_name' => $validatedData['customer_name'],
                        'customer_phone' => $validatedData['customer_phone'],
                        'customer_address' => $validatedData['customer_address'],
                        'total_price' => $totalPrice,
                    ]);
                }
            }

            // Setelah semua pesanan disimpan, kosongkan keranjang
            session()->forget('cart');

            // Beri respons sukses
            return response()->json(['success' => true, 'message' => 'Pesanan berhasil dibuat!'], 200);

        } catch (\Exception $e) {
            // Tangani error dan catat di log untuk debugging
            Log::error('Order Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan pesanan.', 'error' => $e->getMessage()], 500);
        }
    }
}