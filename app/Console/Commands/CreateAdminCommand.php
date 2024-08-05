<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;
use App\Models\User;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'central:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comand to create the admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('secret2024'),
        ]);
    }
}
