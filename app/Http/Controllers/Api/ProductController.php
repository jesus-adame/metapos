<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Branch;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('rows', 10);
        $location = Branch::find(Auth::user()->location_id);

        $products = Product::withStock($location)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request): JsonResponse
    {
        if (is_null($request->code)) {
            return response()->json([]);
        }

        $products = Product::withStock()
            ->where('code', $request->code)
            ->orWhere('name', $request->code)
            ->get();

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'unit_type' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $branchId = Auth::user()->location_id;

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
            'cost' => $request->cost,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'branch_id' => $branchId,
            'unit_type' => $request->unit_type,
        ]);

        foreach (Branch::all() as $branch) {
            Inventory::create([
                'product_id' => $product->id,
                'location_id' => $branch->id,
                'location_type' => Branch::class,
                'quantity' => 0,
                'status' => 'available',
            ]);
        }

        foreach (Warehouse::all() as $warehouse) {
            Inventory::create([
                'product_id' => $product->id,
                'location_id' => $warehouse->id,
                'location_type' => Warehouse::class,
                'quantity' => 0,
                'status' => 'available',
            ]);
        }

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
            'cost' => 'required|numeric',
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
            'cost' => $request->cost,
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
