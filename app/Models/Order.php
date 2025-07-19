<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Definisikan kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'product_id',
        'quantity',
        'customer_name',
        'customer_phone',
        'customer_address',
        'total_price',
    ];

    // Definisikan relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}