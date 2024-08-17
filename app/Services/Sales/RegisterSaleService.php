<?php

namespace App\Services\Sales;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Payment;
use App\Models\CashRegister;
use App\Models\CashFlow;
use App\Models\Branch;
use App\Models\BankTransaction;
use App\Helpers\MathNumberHelper;
use App\Contracts\Locationable;
use App\Contracts\Cashable;

class RegisterSaleService
{
    public function __construct(private CreateInventoryTransaction $inventoryTransactionService) {}

    public function execute(
        ?int $customerId,
        int $sellerId,
        array $products,
        array $paymentMethods,
        int $cashRegisterId,
        ?array $discount = null
    ): array {
        DB::beginTransaction();

        $cashRegister = CashRegister::find($cashRegisterId);
        /** @var Branch */
        $location = $cashRegister->branch;
        $sale = $this->createSale($customerId, $sellerId, $products, $cashRegister, $discount);

        $this->attachSaleProducts($sale, $location, $products);

        // Guardar los métodos de pago
        $paymentsAmount = $this->setPayments($sale, $paymentMethods, $cashRegister);
        $cashPayments = $paymentsAmount['cash'];
        $cardPayments = $paymentsAmount['card'];
        $transferPayments = $paymentsAmount['transfer'];
        $otherPayments = $cardPayments + $transferPayments;

        // Calcular el saldo restante después de aplicar otros métodos de pago
        $remainingBalance = $sale->total - $otherPayments;
        $change = $cashPayments - $remainingBalance;

        if ($cashPayments < $remainingBalance) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Pago insuficiente, quedan $' . number_format($remainingBalance - $cashPayments, 2) . ' faltantes',
            ];
        }

        $sale->status = 'paid';
        $sale->save();

        // Registrar una entrada en caja si el pago fue en efectivo
        if ($change > 0) {
            $this->createCashFlow(
                amount: $change,
                cashable: $sale,
                method: Payment::CASH_METHOD,
                cashRegister: $cashRegister,
                type: 'exit'
            );
        }

        DB::commit();

        return [
            'status' => 'success',
            'message' => 'Venta registrada correctamente',
            'content' => 'Su cambio es $ ' . number_format($change, 2),
            'sale' => $sale,
        ];
    }

    private function createSale(?int $customerId, int $sellerId, array $products, CashRegister $cashRegister, ?array $discount = null): Sale
    {
        $sale = new Sale();
        $sale->customer_id = $customerId;
        $sale->seller_id = $sellerId;
        $sale->cash_register_id = $cashRegister->id;
        $sale->status = 'pending';
        $sale->total = 0; // Inicializamos el total a 0

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

        $sale->total = $calculatedTotal - $discountAmount;
        $sale->save();

        return $sale;
    }

    private function attachSaleProducts(Sale $sale, Locationable $location, array $products)
    {
        foreach ($products as $arrayProduct) {
            /** @var Product */
            $product = Product::find($arrayProduct['id']);
            /** @var int */
            $quantity = $arrayProduct['quantity'];
            /** @var float */
            $price = $arrayProduct['price'];

            $sale->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $this->inventoryTransactionService->execute(
                $location,
                'exit',
                $product->id,
                $quantity,
                $sale->created_at,
                'Venta No. # ' . $sale->id
            );
        }
    }

    private function setPayments(Sale $sale, array $paymentMethods, CashRegister $cashRegister)
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
                'sale_id' => $sale->id,
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
                cashable: $sale,
                method: Payment::CASH_METHOD,
                cashRegister: $cashRegister,
                type: 'entry'
            );
        }

        if ($cardPayments > 0) {
            $this->createCashFlow(
                amount: $cardPayments,
                cashable: $sale,
                method: Payment::CREDIT_CARD_METHOD,
                cashRegister: $cashRegister,
                type: 'entry'
            );
        }

        if ($transferPayments > 0) {
            $this->createCashFlow(
                amount: $transferPayments,
                cashable: $sale,
                method: Payment::TRANSFER_METHOD,
                cashRegister: $cashRegister,
                type: 'entry'
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
