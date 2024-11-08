<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Products\UpdateProductService;
use App\Services\Products\CreateProductService;
use App\Models\Product;
use App\Models\Permission;
use App\Models\Location;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::allows(Permission::VIEW_PRODUCTS);

        $perPage = $request->input('rows', 10);
        $location = Location::find(Auth::user()->location_id);

        $products = Product::withStock($location)
            ->with('categories')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request): JsonResponse
    {
        Gate::allows(Permission::VIEW_PRODUCTS);

        if (is_null($request->code)) {
            return response()->json([]);
        }

        $query = '%' . $request->code . '%';

        $products = Product::withStock()
            ->where('code', 'like', '%' . $query)
            ->orWhere('name', 'like', $query)
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function byCategory(Request $request): JsonResponse
    {
        Gate::allows(Permission::VIEW_PRODUCTS);

        if (is_null($request->category)) {
            return response()->json([]);
        }

        $perPage = $request->input('rows', 10);
        $category = $request->category;

        $products = Product::whereHas('categories', function ($query) use ($category) {
            $query->where('categories.name', $category);
        })->with('categories')->paginate($perPage);

        return response()->json($products);
    }

    public function store(CreateProductRequest $request, CreateProductService $service): JsonResponse
    {
        $product = $service->execute($request, $request->hasFile('image'), $request->file('image'));

        return response()->json([
            'message' => 'Producto registrado correctamente',
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product, UpdateProductService $service): JsonResponse
    {
        $service->execute($product, $request, $request->hasFile('image'), $request->file('image'));

        return response()->json([
            'message' => 'Producto editado correctamente',
            'product' => $product
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        Gate::allows(Permission::DELETE_PRODUCTS);

        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ]);
    }

    public function removeCategory(Product $product, int $category): JsonResponse
    {
        Gate::allows(Permission::UPDATE_PRODUCTS);

        $product->categories()->detach($category);

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ]);
    }
}
