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
        $user = User::where('email', config('app.admin.email'))->first();

        if (is_null($user)) {
            /** @var User */
            $user = User::create([
                'name' => 'Super Admin',
                'lastname' => '',
                'email' => config('app.admin.email'),
                'password' => Hash::make(config('app.admin.password'))
            ]);
        }
    }
}
