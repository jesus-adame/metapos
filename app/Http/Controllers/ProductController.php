<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Products\AllProductsService;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(AllProductsService $service)
    {
        $products = $service->execute();

        return inertia('Products/Index', [
            'title' => 'Productos',
            'products' => $products
        ]);
    }

    public function create(): Response
    {
        return inertia('Products/Create');
    }

    public function edit(Product $product)
    {
        return inertia('Products/Edit', ['product' => $product]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'unit_type' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $branchId = auth()->user()->branch_id;

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
            'price' => $request->price,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'branch_id' => $branchId,
            'unit_type' => $request->unit_type,
        ]);

        return response()->json([
            'message' => 'Producto registrado correctamente',
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'unit_type' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
            'price' => $request->price,
            'unit_type' => $request->unit_type,
            'image' => $imagePath,
            'image_url' => $imageUrl,
        ]);

        return response()->json([
            'message' => 'Producto editado correctamente',
            'product' => $product
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ]);
    }
}
