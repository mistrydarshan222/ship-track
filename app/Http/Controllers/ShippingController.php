<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::with('product')->get();
        $purchases = Purchase::with('product')->get();
        $products = Product::all();
        
        foreach ($products as $product) {
            $product->total_sold = $shippings->where('product_id', $product->id)->sum('picked');
            $product->total_rto = $shippings->where('product_id', $product->id)->sum('rto');
            $product->total_product = $purchases->where('product_id', $product->id)->sum('quantity');
            $product->total_remain = $product->total_product - ($product->total_sold + $product->total_rto);
            $product->total_amount_paid = $purchases->where('product_id', $product->id)->sum(function($purchase) {
                return $purchase->quantity * $purchase->price;
            });
        }
        
        $total_sold_all = $shippings->sum('picked');
        $total_rto_all = $shippings->sum('rto');
        $total_product_all = $purchases->sum('quantity');
        $total_remain_all = $total_product_all - ($total_sold_all + $total_rto_all);
        $total_amount_paid_all = $purchases->sum(function($purchase) {
            return $purchase->quantity * $purchase->price;
        });
        
        return view('dashboard', compact(
            'shippings', 
            'purchases', 
            'products', 
            'total_sold_all', 
            'total_rto_all', 
            'total_product_all', 
            'total_remain_all', 
            'total_amount_paid_all'
        ));
    }
    

    public function store(Request $request)
    {
        Shipping::create($request->all());
        return redirect()->back();
    }

    public function update(Request $request, Shipping $shipping)
    {
        $shipping->update($request->all());
        return redirect()->back();
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return redirect()->back();
    }
}
