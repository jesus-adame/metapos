<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Permission;
use App\Models\Location;
use App\Models\Inventory;
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

        $products = Product::withStock()
            ->where('code', $request->code)
            ->orWhere('name', $request->code)
            ->get();

        return response()->json($products);
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        $locationId = Auth::user()->location_id;

        if ($request->hasFile('image')) {
            // $file = $request->file('image'); // Add name file
            // $imageName = $file->hashName();
            $imagePath = $request->file('image')->store('images', 'public');
            $imageUrl = asset($imagePath);
        } else {
            $imagePath = null;
            $imageUrl = null;
        }

        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'wholesale_price' => $request->wholesale_price ?? 0,
            'price' => $request->price,
            'cost' => $request->cost,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'location_id' => $locationId,
            'unit_type' => $request->unit_type,
            'tax' => $request->tax,
            'has_taxes' => $request->has_taxes == 'true' ? 1 : 0,
        ]);

        foreach (Location::all() as $location) {
            Inventory::create([
                'product_id' => $product->id,
                'location_id' => $location->id,
                'quantity' => 0,
                'status' => 'available',
            ]);
        }

        return response()->json([
            'message' => 'Producto registrado correctamente',
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        if ($request->hasFile('image')) {
            // $file = $request->file('image'); // Add name file
            // $imageName = $file->hashName();
            $disk = Storage::disk('public');

            if ($product->image && $disk->exists($product->image)) {
                $disk->delete($product->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $imageUrl = asset($imagePath);
        } else {
            $imagePath = $product->image;
            $imageUrl = $product->image_url;
        }

        $product->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'wholesale_price' => $request->wholesale_price ?? 0,
            'price' => $request->price,
            'cost' => $request->cost,
            'unit_type' => $request->unit_type,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'tax' => $request->tax,
            'has_taxes' => $request->has_taxes == 'true' ? 1 : 0,
        ]);

        $product->categories()->attach($request['categories']);

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
