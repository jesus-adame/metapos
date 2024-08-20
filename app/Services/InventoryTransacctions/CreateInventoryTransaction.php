<?php

namespace App\Services\InventoryTransacctions;

use App\Models\Location;
use App\Models\InventoryTransaction;
use App\Models\Inventory;

class CreateInventoryTransaction
{
    public function execute(
        Location $location,
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
            'applicated_at' => $date,
            'description' => $description,
        ]);

        $inventory = Inventory::where('product_id', $productId)
            ->where('location_id', $location->id)
            ->first();

        if (is_null($inventory)) {
            $inventory = new Inventory();
            $inventory->product_id = $productId;
            $inventory->location_id = $location->id;
            $inventory->quantity = 0;
            $inventory->status = 'available';
            $inventory->save();
        }

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
