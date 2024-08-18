<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;

class CreateSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::firstOrCreate([
            'key' => 'company_name',
            'label' => 'Nombre de empresa',
            'value' => '',
        ]);

        Setting::firstOrCreate([
            'key' => 'company_address',
            'label' => 'Dirección de la compañía',
            'value' => '',
        ]);

        Setting::firstOrCreate([
            'key' => 'company_phone_number',
            'label' => 'Teléfono de la empresa',
            'value' => '',
        ]);

        Setting::firstOrCreate([
            'key' => 'company_rfc',
            'label' => 'RFC',
            'value' => '',
        ]);

        Setting::firstOrCreate([
            'key' => 'company_email',
            'label' => 'Correo de la empresa',
            'value' => '',
        ]);

        Setting::firstOrCreate([
            'key' => 'company_logo',
            'label' => 'Logo de tu empresa',
            'value' => '',
        ]);
    }
}
