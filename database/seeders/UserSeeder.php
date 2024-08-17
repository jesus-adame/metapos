<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User */
        $user = User::firstOrCreate([
            'name' => 'Admin',
            'lastname' => 'N/A',
            'email' => config('app.admin.email'),
        ]);

        $user->password = Hash::make(config('app.admin.password'));
        $user->save();
    }
}
