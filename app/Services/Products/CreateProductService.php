<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\Location;
use App\Models\Inventory;

class CreateProductService
{
    public function execute($attrs, $author, $hasImage, $image)
    {
        $locationId = $author->location_id;

        if ($hasImage) {
            // $file = $attrs->file('image'); // Add name file
            // $imageName = $file->hashName();
            $imagePath = $image->store('images', 'public');
            $imageUrl = asset($imagePath);
        } else {
            $imagePath = null;
            $imageUrl = null;
        }

        $product = Product::create([
            'name' => $attrs->name,
            'code' => $attrs->code,
            'description' => $attrs->description,
            'wholesale_price' => $attrs->wholesale_price ?? 0,
            'price' => $attrs->price,
            'cost' => $attrs->cost,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'location_id' => $locationId,
            'unit_type' => $attrs->unit_type,
            'tax' => $attrs->tax,
            'has_taxes' => $attrs->has_taxes == 'true' ? 1 : 0,
        ]);

        $product->categories()->attach($attrs['categories']);

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