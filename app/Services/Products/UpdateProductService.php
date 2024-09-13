<?php

namespace App\Services\Products;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class UpdateProductService
{
    public function execute(Product $product, $attrs)
    {
        if ($attrs->hasFile('image')) {
            // $file = $attrs->file('image'); // Add name file
            // $imageName = $file->hashName();
            $disk = Storage::disk('public');

            if ($product->image && $disk->exists($product->image)) {
                $disk->delete($product->image);
            }
            $imagePath = $attrs->file('image')->store('images', 'public');
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
            'has_taxes' => $attrs->has_taxes == 'true' ? 1 : 0,
        ]);

        $product->categories()->attach($attrs['categories']);

        return $product;
    }
}
