<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $totalProducts = Product::count();
        $totalActiveUsers = User::count();
        return view('dashboard', compact('totalProducts', 'totalActiveUsers', 'totalSalesToday'));
    }
}
