<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'image_url',
        'unit_type',
        'tax',
    ];

    protected $casts = [
        'price' => 'float',
    ];
}
