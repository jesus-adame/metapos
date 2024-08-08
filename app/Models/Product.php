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
        'branch_id',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
