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

        /** @var CashRegister */
        $cashRegister = CashRegister::find($cashRegisterId);
        /** @var Branch */
        $location = $cashRegister->branch;

        /** @var Purchase */
        $purchase = $this->createPurchase($supplierId, $buyerId, $purchaseDate, $location, $products);
        $this->attachPurchaseProducts($purchase, $location, $products);

        if (!$updateCashRegister) {
            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Compra registrada correctamente',
                'content' => ''
            ];
        }

        $paymentsAmount = $this->setPayments($purchase, $paymentMethods, $cashRegister);

        if (!$this->validateCashRegisterBalance($cashRegister, $paymentsAmount)) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => 'Caja sin fondos',
                'content' => ''
            ];
        }

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

        if ($cashPayments > 0) {
            $this->createCashFlow(
                amount: $cashPayments,
                cashable: $purchase,
                method: Payment::CASH_METHOD,
                cashRegister: $cashRegister,
                type: 'exit',
                dateTime: $purchaseDate
            );
        }

        if ($cardPayments > 0) {
            $this->createCashFlow(
                amount: $cardPayments,
                cashable: $purchase,
                method: Payment::CREDIT_CARD_METHOD,
                cashRegister: $cashRegister,
                type: 'exit',
                dateTime: $purchaseDate
            );
        }

        if ($transferPayments > 0) {
            $this->createCashFlow(
                amount: $transferPayments,
                cashable: $purchase,
                method: Payment::TRANSFER_METHOD,
                cashRegister: $cashRegister,
                type: 'exit',
                dateTime: $purchaseDate
            );
        }

        DB::commit();

        return [
            'status' => 'success',
            'message' => 'Compra registrada correctamente',
            'content' => 'Su cambio es $ ' . number_format($change, 2)
        ];
    }

    private function validateCashRegisterBalance(CashRegister $cashRegister, array $paymentsAmount)
    {
        $totalAmount = $paymentsAmount['cash'] + $paymentsAmount['card'] + $paymentsAmount['transfer'];
        if ($totalAmount > $cashRegister->getBalance()) {
            return false;
        }
        return true;
    }

    private function createPurchase(?int $supplierId, int $buyerId, mixed $purchaseDate, Locationable $location, array $products)
    {
        $purchase = Purchase::create([
            'supplier_id' => $supplierId,
            'buyer_id' => $buyerId,
            'total' => 0, // Calcularemos el total a continuación
            'purchase_date' => $purchaseDate->format('Y-m-d 00:00:00'),
            'status' => 'paid',
            'location_id' => $location->id,
            'location_type' => $location::class,
        ]);

        $total = 0;
        foreach ($products as $productData) {
            $quantity = $productData['quantity'];
            $cost = $productData['cost'];
            $total += $cost * $quantity;
        }

        // Actualizar el total de la compra
        $purchase->update(['total' => $total]);

        return $purchase;
    }

    private function attachPurchaseProducts(Purchase $purchase, Locationable $location, array $products)
    {
        foreach ($products as $productData) {
            /** @var Product */
            $product = Product::find($productData['id']);
            /** @var int */
            $quantity = $productData['quantity'];
            /** @var float */
            $cost = $productData['cost'];

            $purchase->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $cost,
            ]);

            $this->inventoryTransactionService->execute(
                $location,
                'entry',
                $product->id,
                $quantity,
                $purchase->created_at->format('Y-m-d'),
                'Carga por compra #' . $purchase->id
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
        $description = 'Entrada por ' . $cashable->getClassName() . ' # ' . $cashable->id;

        if ($type == 'exit') {
            $description = 'Salida por ' . $cashable->getClassName() . ' # ' . $cashable->id;
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
