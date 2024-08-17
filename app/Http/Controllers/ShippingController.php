<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    $logs = ChangeLog::with('user')->orderBy('created_at', 'desc')->get();

    return view('dashboard', compact(
        'shippings', 
        'purchases', 
        'products', 
        'total_sold_all', 
        'total_rto_all', 
        'total_product_all', 
        'total_remain_all', 
        'total_amount_paid_all',
        'logs'
    ));
}

    

    public function store(Request $request)
    {
        $shipping = Shipping::create($request->all());

        // Log the creation
        $this->logChange('Shipping', $shipping->id, 'created', $shipping->toArray());

        return redirect()->back();
    }

    public function update(Request $request, Shipping $shipping)
    {
        $originalData = $shipping->toArray();
        $shipping->update($request->all());

        // Log the update
        $this->logChange('Shipping', $shipping->id, 'updated', [
            'before' => $originalData,
            'after' => $shipping->toArray()
        ]);

        return redirect()->back();
    }

    public function destroy(Shipping $shipping)
    {
        $originalData = $shipping->toArray();
        $shipping->delete();

        // Log the deletion
        $this->logChange('Shipping', $shipping->id, 'deleted', $originalData);

        return redirect()->back();
    }

    protected function logChange($model, $modelId, $action, $changes)
    {
        ChangeLog::create([
            'user_id' => Auth::id(),
            'model' => $model,
            'model_id' => $modelId,
            'action' => $action,
            'changes' => json_encode($changes),
        ]);
    }
}
