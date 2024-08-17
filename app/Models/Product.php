<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }
}
