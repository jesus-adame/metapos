<?php

namespace App\Services\InventoryTransacctions;

use App\Models\InventoryTransaction;
use App\Models\Inventory;
use App\Contracts\Locationable;

class CreateInventoryTransaction
{
    public function execute(
        Locationable $location,
        string $type,
        int $productId,
        int $amount,
        mixed $date,
        ?string $description = null
    ) {
        $transaction = InventoryTransaction::create([
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $amount,
            'transaction_date' => $date,
            'description' => $description,
        ]);

        $inventory = Inventory::where('product_id', $productId)
            ->where('location_id', $location->id)
            ->where('location_type', $location::class)
            ->first();

        // Actualizar el stock del producto
        if ($type == 'entry') {
            $inventory->quantity += $amount;
        } else {
            $inventory->quantity -= $amount;
        }
        $inventory->save();

        return $transaction;
    }
}
