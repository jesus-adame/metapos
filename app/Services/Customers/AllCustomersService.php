<?php

namespace App\Services\Customers;

use App\Models\Customer;

class AllCustomersService
{
    public function execute()
    {
        return Customer::all();
    }
}
