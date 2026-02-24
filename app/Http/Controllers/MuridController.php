<?php

// Lokasi: app/Http/Controllers/MuridController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MuridController extends Controller
{
    public function index()
    {
        return view('murid.dashboard');
    }
}