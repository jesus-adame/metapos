<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Services\Users\AllUsersService;
use App\Services\Suppliers\AllSuppliersService;
use App\Services\Customers\AllCustomersService;
use App\Models\Permission;

class UserController extends Controller
{
    public function __construct(
        private AllUsersService $allUsersService,
        private AllCustomersService $allCustomersService,
        private AllSuppliersService $allSuppliersService
    ) {}

    public function index()
    {
        Gate::authorize(Permission::VIEW_USERS);

        return Inertia::render('User/Index');
    }
}
