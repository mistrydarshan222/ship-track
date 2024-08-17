<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'model', 'model_id', 'action', 'changes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
