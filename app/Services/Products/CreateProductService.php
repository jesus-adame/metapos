<?php

namespace App\Services\Products;

use Maestroerror\HeicToJpg;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\Currency;

class CreateProductService
{
    public function execute($attrs, $hasImage, $image)
    {
        $currency = Currency::where('name', 'MXN')->first();

        if ($hasImage) {
            // Obtener la extensión original del archivo
            $extension = strtolower($image->getClientOriginalExtension());

            // Si la imagen es HEIC, la convertimos a JPG
            if ($extension === 'heic') {
                $heicConverter = new HeicToJpg();
                $jpgContent = $heicConverter->convert($image->getPathname())->get();
                $jpgFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg';

                // Guardar la imagen JPG en la carpeta 'images'
                $imagePath = 'images/' . $jpgFilename;
                Storage::disk('public')->put($imagePath, $jpgContent);
            } else {
                // Si no es HEIC, guardamos la imagen directamente
                $imagePath = $image->store('images', 'public');
            }

            $imageUrl = asset('storage/' . $imagePath);
        } else {
            $imagePath = null;
            $imageUrl = null;
        }

        /** @var Product */
        $product = Product::create([
            'name' => $attrs->name,
            'code' => $attrs->code,
            'description' => $attrs->description,
            'wholesale_price' => $attrs->wholesale_price ?? 0,
            'price' => $attrs->price,
            'cost' => $attrs->cost,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'unit_type' => $attrs->unit_type,
            'tax' => $attrs->tax,
        ]);

        $product->categories()->attach($attrs['categories']);
        $product->currency()->associate($currency);
        $product->save();

        foreach (Location::all() as $location) {
            Inventory::create([
                'product_id' => $product->id,
                'location_id' => $location->id,
                'quantity' => 0,
                'status' => 'available',
            ]);
        }

        return $product;
    }
}
