<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'picked', 'rto', 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
