<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function create()
    {
        return view('dashboard');
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return redirect()->back();
    }

    
    public function update(Request $request, Product $Product)
    {
        $Product->update($request->all());
        return redirect()->back();
    }

}
