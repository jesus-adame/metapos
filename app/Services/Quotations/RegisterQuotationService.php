<?php

namespace App\Services\Quotations;

use Illuminate\Support\Facades\DB;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Quotation;
use App\Models\Product;
use App\Helpers\MathNumberHelper;

class RegisterQuotationService
{
    public function __construct(private CreateInventoryTransaction $inventoryTransactionService) {}

    public function execute(
        ?int $customerId,
        int $sellerId,
        array $products,
        ?array $discount = null,
        bool $wholesale = false,
    ): array {
        DB::beginTransaction();

        /** @var Quotation */
        $quotation = $this->createQuotation($customerId, $sellerId, $products, $discount, $wholesale);
        $this->attachSaleProducts($quotation, $products);

        // Guardar los métodos de pago
        $quotation->status = 'completed';
        $quotation->save();

        DB::commit();

        return [
            'status' => 'success',
            'message' => 'Cotización registrada correctamente',
            'quotation' => $quotation,
        ];
    }

    private function createQuotation(
        ?int $customerId,
        int $sellerId,
        array $products,
        ?array $discount = null,
    ): Quotation {
        $quotation = new Quotation();
        $quotation->customer_id = $customerId;
        $quotation->seller_id = $sellerId;
        $quotation->status = 'pending';
        $quotation->total = 0; // Inicializamos el total a 0

        $calculatedTotal = 0;
        foreach ($products as $arrayProduct) {
            /** @var int */
            $quantity = $arrayProduct['quantity'];
            /** @var float */
            $price = $arrayProduct['price'];

            $calculatedTotal += $price * $quantity;
        }

        $discountAmount = 0;

        if (!is_null($discount)) {
            if ($discount['type'] == 'percentage') {
                $discountAmount = MathNumberHelper::getPercentage($calculatedTotal, $discount['amount']);
            } else {
                $discountAmount = $discount['amount'];
            }
        }

        $quotation->total = $calculatedTotal - $discountAmount;
        $quotation->save();

        return $quotation;
    }

    private function attachSaleProducts(Quotation $quotation, array $products)
    {
        foreach ($products as $arrayProduct) {
            /** @var Product */
            $product = Product::find($arrayProduct['id']);
            /** @var int */
            $quantity = $arrayProduct['quantity'];
            /** @var float */
            $price = $arrayProduct['price'];
            /** @var float */
            $taxes = $arrayProduct['tax'] ?? 0;

            $quotation->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $price,
                'has_taxes' => 1,
                'tax' => $taxes,
            ]);
        }
    }
}
