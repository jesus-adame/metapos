<?php

namespace App\Services\Sales;

use App\Models\Sale;

class AllSalesService
{
    public function execute()
    {
        return Sale::with('customer', 'payments', 'seller', 'cashRegister')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
