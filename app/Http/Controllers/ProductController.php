<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return inertia('Products/Index');
    }

    public function create(): Response
    {
        return inertia('Products/Create');
    }

    public function edit(Product $product)
    {
        return inertia('Products/Edit', ['product' => $product]);
    }
}
