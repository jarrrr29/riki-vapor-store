<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Untuk halaman home, kita hanya me-return view 'home' tanpa data khusus
        return view('home');
    }
}