<?php

namespace App\Services\Sales;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Sale;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\Location;
use App\Models\Currency;
use App\Models\CashRegister;
use App\Models\CashFlow;
use App\Helpers\MathNumberHelper;
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
        ?array $discount = null,
        bool $wholesale = false,
    ): array {
        DB::beginTransaction();

        $cashRegister = CashRegister::find($cashRegisterId);
        /** @var Location */
        $location = $cashRegister->location;
        $sale = $this->createSale($customerId, $sellerId, $products, $cashRegister, $discount, $wholesale);

        $this->attachSaleProducts($sale, $location, $products);

        // Guardar los métodos de pago
        $paymentsAmount = $this->setPayments($sale, $paymentMethods);
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

        $sale->status = 'completed';
        $sale->change = $change;
        $sale->save();

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

    private function createSale(
        ?int $customerId,
        int $sellerId,
        array $products,
        CashRegister $cashRegister,
        ?array $discount = null,
    ): Sale {
        $currency = Currency::where('name', 'MXN')->first();

        $sale = new Sale();
        $sale->customer_id = $customerId;
        $sale->seller_id = $sellerId;
        $sale->cash_register_id = $cashRegister->id;
        $sale->status = 'pending';
        $sale->total = 0; // Inicializamos el total a 0
        $lineTotals = 0;

        foreach ($products as $arrayProduct) {
            /** @var Product */
            $product = Product::find($arrayProduct['id']);
            /** @var int */
            $quantity = $arrayProduct['quantity'];
            /** @var float */
            $price = $product->price;
            /** @var float */
            $tax_rate = $product->tax / 100;
            /** @var float */
            $tax_amount = round($product->price * $tax_rate, 6);

            $lineTotals += round($price + $tax_amount, 2) * $quantity;
        }

        $discountAmount = 0;

        if (!is_null($discount)) {
            if ($discount['type'] == 'percentage') {
                $discountAmount = MathNumberHelper::getPercentage($lineTotals, $discount['amount']);
            } else {
                $discountAmount = $discount['amount'];
            }
        }

        $sale->currency()->associate($currency);
        $sale->total = $lineTotals - $discountAmount;
        $sale->save();

        return $sale;
    }

    private function attachSaleProducts(Sale $sale, Location $location, array $products)
    {
        foreach ($products as $arrayProduct) {
            /** @var Product */
            $product = Product::find($arrayProduct['id']);
            /** @var int */
            $quantity = $arrayProduct['quantity'];
            /** @var float */
            $price = $product->price;
            /** @var float */
            $tax_rate = $product->tax / 100;
            /** @var float */
            $tax_amount = round($product->price * $tax_rate, 6);

            $sale->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $price,
                'tax' => $tax_amount,
                'tax_rate' => $tax_rate,
                'subtotal' => $price * $quantity,
                'line_total' => round($price + $tax_amount, 2) * $quantity,
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

    private function setPayments(Sale $sale, array $paymentMethods)
    {
        $cashPayments = 0;
        $cardPayments = 0;
        $transferPayments = 0;

        foreach ($paymentMethods as $payment) {
            /** @var PaymentMethod */
            $method = PaymentMethod::where('code', $payment['method'])->first();
            /** @var float */
            $amount = $payment['amount'] ?? 0;

            Payment::create([
                'payable_id' => $sale->id,
                'payable_type' => $sale::class,
                'payment_method_id' => $method->id,
                'amount' => $amount,
            ]);

            if ($method->code === 'cash') {
                $cashPayments += $amount;
            } elseif ($method->code === 'card') {
                $cardPayments += $amount;
            } elseif ($method->code === 'transfer') {
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
            $description = 'Cambio de ' . $cashable->getClassName() . ' # ' . $cashable->id;
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
    }
}
