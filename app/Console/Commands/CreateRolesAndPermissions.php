<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class CreateRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:basic-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Role::create(['name' => 'Super admin']);
        $seller = Role::create(['name' => 'seller']);
        $reader = Role::create(['name' => 'reader']);

        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'update products']);
        Permission::create(['name' => 'delete products']);

        Permission::create(['name' => 'view sales']);
        Permission::create(['name' => 'create sales']);
        Permission::create(['name' => 'delete sales']);

        Permission::create(['name' => 'view purchases']);
        Permission::create(['name' => 'create purchases']);
        Permission::create(['name' => 'update purchases']);

        Permission::create(['name' => 'view cashflows']);
        Permission::create(['name' => 'update cashflows']);
        Permission::create(['name' => 'delete cashflows']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view finance']);

        Permission::create(['name' => 'view company']);
        Permission::create(['name' => 'update company']);

        $seller->givePermissionTo('view sales');
        $seller->givePermissionTo('create sales');
        $seller->givePermissionTo('view cashflows');
        $seller->givePermissionTo('update cashflows');

        $reader->givePermissionTo('view products');
        $reader->givePermissionTo('view sales');
        $reader->givePermissionTo('view purchases');
        $reader->givePermissionTo('view users');
    }
}
