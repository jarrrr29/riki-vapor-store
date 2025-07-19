<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item['harga'] * $item['quantity'];
        }

        return view('keranjang', compact('cartItems', 'totalPrice'));
    }

    /**
     * Tambahkan produk ke keranjang belanja (menggunakan session).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function tambah(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan!'], 404);
        }

        $quantity = $request->input('quantity', 1);

        if (!is_numeric($quantity) || $quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Kuantitas tidak valid!'], 400);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                "id" => $product->id,
                "nama" => $product->nama,
                "quantity" => $quantity,
                "harga" => $product->harga,
                "gambar" => $product->gambar
            ];
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang!', 'cartCount' => $cartCount]);
    }

    /**
     * Perbarui kuantitas produk di keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQuantity(Request $request, $productId)
    {
        $action = $request->input('action'); // 'increase', 'decrease', atau 'set'
        $newQuantity = $request->input('quantity'); // Kuantitas baru jika action 'set'

        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ada di keranjang.'], 404);
        }

        switch ($action) {
            case 'increase':
                $cart[$productId]['quantity']++;
                break;
            case 'decrease':
                $cart[$productId]['quantity']--;
                break;
            case 'set':
                if (!is_numeric($newQuantity) || $newQuantity < 1) {
                    return response()->json(['success' => false, 'message' => 'Kuantitas tidak valid.'], 400);
                }
                $cart[$productId]['quantity'] = $newQuantity;
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
        }

        if ($cart[$productId]['quantity'] < 1) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['harga'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas produk berhasil diperbarui.',
            'cartCount' => $cartCount,
            'totalPrice' => $totalPrice,
            'itemQuantity' => isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0,
        ]);
    }

    /**
     * Hapus produk dari keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ada di keranjang.'], 404);
        }

        unset($cart[$productId]);

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['harga'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang.',
            'cartCount' => $cartCount,
            'totalPrice' => $totalPrice,
        ]);
    }
}