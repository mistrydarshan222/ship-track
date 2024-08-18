<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Store a newly created purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $purchase = Purchase::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'date' => $request->date,
        ]);

        // Log the creation
        $this->logChange('Purchase', $purchase->id, 'created', $purchase->toArray());

        return redirect()->back();
    }

    /**
     * Update the specified purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $purchase = Purchase::findOrFail($id);
        $originalData = $purchase->toArray();
        $purchase->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'date' => $request->date,
        ]);

        // Log the update
        $this->logChange('Purchase', $purchase->id, 'updated', [
            'before' => $originalData,
            'after' => $purchase->toArray()
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified purchase from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $originalData = $purchase->toArray();
        $purchase->delete();

        // Log the deletion
        $this->logChange('Purchase', $purchase->id, 'deleted', $originalData);

        return redirect()->route('dashboard');
    }

    /**
     * Log changes made to the purchase.
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
