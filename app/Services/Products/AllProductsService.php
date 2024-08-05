<?php

namespace App\Services\Products;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

class AllProductsService
{
    /**
     * @return Product[]
     */
    public function execute(): Collection
    {
        return Product::orderBy('updated_at', 'desc')->get();
    }
}
