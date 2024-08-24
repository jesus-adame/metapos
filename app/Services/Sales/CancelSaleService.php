<?php

namespace App\Services\Sales;

use Illuminate\Support\Facades\DB;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Location;

class CancelSaleService
{
    public function __construct(private CreateInventoryTransaction $inventoryTransactionService) {}

    public function execute(Sale $sale): array
    {
        DB::beginTransaction();

        $cashRegister = $sale->cashRegister;
        /** @var Location */
        $location = $cashRegister->location;

        $sale->status = 'canceled';
        $sale->save();

        $this->returnProducts($sale, $location);

        DB::commit();

        return [
            'status' => 'success',
            'message' => 'Venta cancelada.',
            'sale' => $sale,
        ];
    }

    private function returnProducts(Sale $sale, Location $location)
    {
        /** @var Product */
        foreach ($sale->products as $product) {
            $this->inventoryTransactionService->execute(
                $location,
                'entry',
                $product->id,
                $product->pivot->quantity,
                $sale->updated_at,
                'Devolución de venta No. # ' . $sale->id
            );
        }
    }
}
