<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create()
    {
        return view('dashboard');
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());

        // Log the creation
        $this->logChange('Product', $product->id, 'created', $product->toArray());

        return redirect()->back();
    }

    public function update(Request $request, Product $product)
    {
        $originalData = $product->toArray();
        $product->update($request->all());

        // Log the update
        $this->logChange('Product', $product->id, 'updated', [
            'before' => $originalData,
            'after' => $product->toArray()
        ]);

        return redirect()->back();
    }

    public function destroy(Product $product)
    {
        $originalData = $product->toArray();
        $product->delete();

        // Log the deletion
        $this->logChange('Product', $product->id, 'deleted', $originalData);

        return redirect()->back();
    }

    /**
     * Log changes made to the product.
     */
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
