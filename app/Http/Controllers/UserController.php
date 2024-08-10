<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\Users\AllUsersService;
use App\Services\Suppliers\AllSuppliersService;
use App\Services\Customers\AllCustomersService;

class UserController extends Controller
{
    public function __construct(
        private AllUsersService $allUsersService,
        private AllCustomersService $allCustomersService,
        private AllSuppliersService $allSuppliersService
    ) {}

    public function index()
    {
        $users = $this->allUsersService->execute();
        $customers = $this->allCustomersService->execute();
        $suppliers = $this->allSuppliersService->execute();

        return Inertia::render('User/Index', [
            'title' => 'Usuarios',
            'users' => $users,
            'customers' => $customers,
            'suppliers' => $suppliers,
        ]);
    }
}
