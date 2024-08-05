<?php

namespace App\Services\Suppliers;

use App\Models\Supplier;

class AllSuppliersService
{
    public function execute()
    {
        return Supplier::all();
    }
}
