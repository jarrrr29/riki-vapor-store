<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // ID produk yang dipesan
            $table->integer('quantity'); // Kuantitas produk
            $table->string('customer_name'); // Nama pelanggan
            $table->string('customer_phone'); // Nomor HP pelanggan
            $table->text('customer_address'); // Alamat pelanggan
            $table->decimal('total_price', 10, 2); // Total harga pesanan (qty * harga produk)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
