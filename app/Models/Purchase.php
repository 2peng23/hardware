<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $casts = [
        'product_id' => 'array',
        'product_quantity' => 'array'
    ];
}
