<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Area;

class AuthController extends Controller
{
    public function index()
    {
        $shops = Shop::with(['genre', 'area'])->get();
        return view('index');
    }
}
