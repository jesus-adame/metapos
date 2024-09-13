<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::firstOrCreate([
            'code' => 'cash',
            'name' => 'Efectivo',
            'description' => 'Pago en efectivo',
        ]);

        PaymentMethod::firstOrCreate([
            'code' => 'card',
            'name' => 'Tarjeta',
            'description' => 'Pago con tarjeta',
        ]);

        PaymentMethod::firstOrCreate([
            'code' => 'transfer',
            'name' => 'Transferencia',
            'description' => 'Pago por transferencia',
        ]);
    }
}
