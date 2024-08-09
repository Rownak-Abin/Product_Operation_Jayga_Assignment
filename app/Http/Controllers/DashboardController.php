<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ApiResponderTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponderTrait;

    public function index()
    {
        $products = Product::with('category', 'attributes')->get();
        return view('dashboard', compact('products'));
    }
}
