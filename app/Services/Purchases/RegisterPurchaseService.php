<?php

namespace App\Services\Purchases;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Payment;
use App\Models\CashRegister;
use App\Models\CashFlow;
use App\Models\Branch;
use App\Models\BankTransaction;
use App\Contracts\Locationable;
use App\Contracts\Cashable;

class RegisterPurchaseService
{
    public function __construct(private CreateInventoryTransaction $inventoryTransactionService) {}

    public function execute(
        $supplierId,
        $buyerId,
        $purchaseDate,
        $products,
        bool $updateCashRegister,
        int $cashRegisterId,
        ?array $paymentMethods = null,
    ): array {
        DB::beginTransaction();

        $cashRegister = CashRegister::find($cashRegisterId);
        /** @var Branch */
        $location = $cashRegister->branch;

        $purchase = Purchase::create([
            'supplier_id' => $supplierId,
            'buyer_id' => $buyerId,
            'total' => 0, // Calcularemos el total a continuación
            'purchase_date' => $purchaseDate->format('Y-m-d'),
            'status' => 'paid',
            'location_id' => $location->id,
            'location_type' => $location::class,
        ]);

        $total = 0;
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['quantity'];
            $cost = $productData['cost'];

            $purchase->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $cost,
            ]);

            $total += $cost * $quantity;

            $this->inventoryTransactionService->execute(
                $location,
                'entry',
                $product->id,
                $quantity,
                $purchaseDate->format('Y-m-d'),
                'Carga por compra #' . $purchase->id
            );
        }

        // Actualizar el total de la compra
        $purchase->update(['total' => $total]);

        if (!$updateCashRegister) {
            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Compra registrada correctamente',
                'content' => ''
            ];
        }

        $paymentsAmount = $this->setPayments($purchase, $paymentMethods, $cashRegister);

        $cashPayments = $paymentsAmount['cash'];
        $cardPayments = $paymentsAmount['card'];
        $transferPayments = $paymentsAmount['transfer'];
        $otherPayments = $cardPayments + $transferPayments;

        // Calcular el saldo restante después de aplicar otros métodos de pago
        $remainingBalance = $purchase->total - $otherPayments;
        $change = $cashPayments - $remainingBalance;

        if ($cashPayments < $remainingBalance) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Pago insuficiente, quedan $' . number_format($remainingBalance - $cashPayments, 2) . ' faltantes',
            ];
        }

        DB::commit();

        return [
            'status' => 'success',
            'message' => 'Compra registrada correctamente',
            'content' => 'Su cambio es $ ' . number_format($change, 2)
        ];
    }

    private function attachPurchaseProducts(Purchase $pruchase, Locationable $location, array $products)
    {
        foreach ($products as $arrayProduct) {
            /** @var Product */
            $product = Product::find($arrayProduct['id']);
            /** @var int */
            $quantity = $arrayProduct['quantity'];
            /** @var float */
            $price = $arrayProduct['price'];

            $pruchase->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $this->inventoryTransactionService->execute(
                $location,
                'exit',
                $product->id,
                $quantity,
                $pruchase->created_at,
                'Compra No. # ' . $pruchase->id
            );
        }
    }

    private function setPayments(Purchase $purchase, array $paymentMethods, CashRegister $cashRegister)
    {
        $cashPayments = 0;
        $cardPayments = 0;
        $transferPayments = 0;

        foreach ($paymentMethods as $payment) {
            /** @var string */
            $method = $payment['method'];
            /** @var float */
            $amount = $payment['amount'] ?? 0;

            Payment::create([
                'purchase_id' => $purchase->id,
                'method' => $method,
                'amount' => $amount,
            ]);

            if ($method === 'cash') {
                $cashPayments += $amount;
            } elseif ($method === 'card') {
                $cardPayments += $amount;
            } elseif ($method === 'transfer') {
                $transferPayments += $amount;
            }
        }

        if ($cashPayments > 0) {
            $this->createCashFlow(
                amount: $cashPayments,
                cashable: $purchase,
                method: Payment::CASH_METHOD,
                cashRegister: $cashRegister,
                type: 'exit'
            );
        }

        if ($cardPayments > 0) {
            $this->createCashFlow(
                amount: $cardPayments,
                cashable: $purchase,
                method: Payment::CREDIT_CARD_METHOD,
                cashRegister: $cashRegister,
                type: 'exit'
            );
        }

        if ($transferPayments > 0) {
            $this->createCashFlow(
                amount: $transferPayments,
                cashable: $purchase,
                method: Payment::TRANSFER_METHOD,
                cashRegister: $cashRegister,
                type: 'exit'
            );
        }

        return [
            'cash' => $cashPayments,
            'card' => $cardPayments,
            'transfer' => $transferPayments,
        ];
    }

    private function createCashFlow(
        Cashable $cashable,
        float $amount,
        string $method,
        CashRegister $cashRegister,
        string $type = 'entry',
        \DateTime $dateTime = null
    ) {
        $description = 'Entrada en ' . $method . ' ' . $cashable->getClassName() . ' # ' . $cashable->id;

        if ($type == 'exit') {
            $description = 'Salida en ' . $method . ' ' . $cashable->getClassName() . ' # ' . $cashable->id;
        }

        if (is_null($dateTime)) {
            $dateTime = Carbon::now();
        }

        CashFlow::create([
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'cashable_id' => $cashable->id,
            'cashable_type' => $cashable::class,
            'method' => $method,
            'date' => $dateTime,
            'cash_register_id' => $cashRegister->id,
        ]);

        if ($method == 'card' || $method == 'transfer') {
            BankTransaction::create([
                'type' => 'entry',
                'amount' => $amount,
                'description' => 'Pago por tarjeta venta # ' . $cashable->id,
                'transaction_date' => Carbon::now(),
            ]);
        }
    }
}
