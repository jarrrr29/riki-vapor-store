<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hanya menampilkan view 'home' tanpa mengirim data
        return view('home');
    }
}