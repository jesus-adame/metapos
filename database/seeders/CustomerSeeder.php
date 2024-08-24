<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::firstOrCreate([
            'name' => 'Público general',
            'lastname' => '',
            'email' => 'general@metapos.mx',
            'phone' => '7777777',
            'address' => 'N/A',
        ]);
    }
}
