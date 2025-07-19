<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Definisikan kolom-kolom yang boleh diisi secara massal (mass assignable)
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'gambar',
        'kategori' // Pastikan kolom 'kategori' ada di sini
    ];

    // Jika Anda punya relasi dengan kategori lain, bisa ditambahkan di sini
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}