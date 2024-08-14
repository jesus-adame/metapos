<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\Permission;

class ProductController extends Controller
{
    public function index()
    {
        Gate::authorize(Permission::VIEW_PRODUCTS);

        return inertia('Products/Index');
    }

    public function create(): Response
    {
        Gate::authorize(Permission::CREATE_PRODUCTS);

        return inertia('Products/Create');
    }

    public function edit(Product $product)
    {
        Gate::authorize(Permission::UPDATE_PRODUCTS);

        return inertia('Products/Edit', ['product' => $product]);
    }
}
