<?php

namespace App\Services\InventoryTransacctions;

use App\Models\Product;
use App\Models\InventoryTransaction;

class CreateInventoryTransaction
{
    public function execute(string $type, int $productId, int $quantity, mixed $date, string $description = '')
    {
        $transaction = InventoryTransaction::create([
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $quantity,
            'transaction_date' => $date,
            'description' => $description,
        ]);

        // Actualizar el stock del producto
        $product = Product::find($productId);
        if ($type == 'entry') {
            $product->stock += $quantity;
        } else {
            $product->stock -= $quantity;
        }
        $product->save();

        return $transaction;
    }
}
