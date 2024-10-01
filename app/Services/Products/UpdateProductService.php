<?php

namespace App\Services\Products;

use Maestroerror\HeicToJpg;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Currency;

class UpdateProductService
{
    public function execute(Product $product, Request $attrs, $hasImage, $image)
    {
        $currency = Currency::where('name', 'MXN')->first();

        if ($hasImage) {
            $disk = Storage::disk('public');
            $extension = strtolower($image->getClientOriginalExtension());

            if ($product->image && $disk->exists($product->image)) {
                $disk->delete($product->image);
            }

            if ($extension === 'heic') {
                // Convertir HEIC a JPG en memoria
                $heicConverter = new HeicToJpg();
                $jpgContent = $heicConverter->convert($image->getPathname())->get();
                $jpgFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg';

                // Guardar la imagen JPG
                $imagePath = 'images/' . $jpgFilename;
                Storage::disk('public')->put($imagePath, $jpgContent);
            } else {
                // Si no es HEIC, guardamos la imagen directamente
                $imagePath = $image->store('images', 'public');
            }

            $imageUrl = asset($imagePath);
        } else {
            $imagePath = $product->image;
            $imageUrl = $product->image_url;
        }

        $product->update([
            'code' => $attrs->code,
            'name' => $attrs->name,
            'description' => $attrs->description,
            'wholesale_price' => $attrs->wholesale_price ?? 0,
            'price' => $attrs->price,
            'cost' => $attrs->cost,
            'unit_type' => $attrs->unit_type,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'tax' => $attrs->tax,
        ]);

        $product->categories()->attach($attrs['categories']);
        $product->currency()->associate($currency);
        $product->save();

        return $product;
    }
}
