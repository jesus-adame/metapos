<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class CreateRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User */
        $superAdmin = User::where('name', 'Super Admin')->first();

        Role::firstOrCreate(['name' => Role::SUPER_ADMIN]);
        $manager = Role::firstOrCreate(['name' => Role::MANAGER]);
        $seller = Role::firstOrCreate(['name' => Role::SELLER]);
        $warehouseman = Role::firstOrCreate(['name' => Role::WAREHOUSEMAN]);
        $reader = Role::firstOrCreate(['name' => Role::READER]);

        Permission::firstOrCreate(['name' => 'view products']);
        Permission::firstOrCreate(['name' => 'create products']);
        Permission::firstOrCreate(['name' => 'update products']);
        Permission::firstOrCreate(['name' => 'delete products']);

        Permission::firstOrCreate(['name' => 'view sales']);
        Permission::firstOrCreate(['name' => 'create sales']);
        Permission::firstOrCreate(['name' => 'update sales']);
        Permission::firstOrCreate(['name' => 'delete sales']);

        Permission::firstOrCreate(['name' => 'view purchases']);
        Permission::firstOrCreate(['name' => 'create purchases']);
        Permission::firstOrCreate(['name' => 'update purchases']);
        Permission::firstOrCreate(['name' => 'delete purchases']);

        Permission::firstOrCreate(['name' => 'view cashflows']);
        Permission::firstOrCreate(['name' => 'update cashflows']);
        Permission::firstOrCreate(['name' => 'delete cashflows']);

        Permission::firstOrCreate(['name' => 'view locations']);
        Permission::firstOrCreate(['name' => 'update locations']);
        Permission::firstOrCreate(['name' => 'delete locations']);

        Permission::firstOrCreate(['name' => 'view warehouses']);
        Permission::firstOrCreate(['name' => 'update warehouses']);
        Permission::firstOrCreate(['name' => 'delete warehouses']);

        Permission::firstOrCreate(['name' => 'view customers']);
        Permission::firstOrCreate(['name' => 'create customers']);
        Permission::firstOrCreate(['name' => 'update customers']);
        Permission::firstOrCreate(['name' => 'delete customers']);

        Permission::firstOrCreate(['name' => 'view suppliers']);
        Permission::firstOrCreate(['name' => 'create suppliers']);
        Permission::firstOrCreate(['name' => 'update suppliers']);
        Permission::firstOrCreate(['name' => 'delete suppliers']);

        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'update users']);
        Permission::firstOrCreate(['name' => 'delete users']);

        Permission::firstOrCreate(['name' => 'view categories']);
        Permission::firstOrCreate(['name' => 'create categories']);
        Permission::firstOrCreate(['name' => 'update categories']);
        Permission::firstOrCreate(['name' => 'delete categories']);

        Permission::firstOrCreate(['name' => 'create permissions']);
        Permission::firstOrCreate(['name' => 'update permissions']);
        Permission::firstOrCreate(['name' => 'delete permissions']);

        Permission::firstOrCreate(['name' => 'view finances']);

        Permission::firstOrCreate(['name' => 'view settings']);
        Permission::firstOrCreate(['name' => 'update settings']);

        $manager->givePermissionTo('view finances');
        $manager->givePermissionTo('view settings');
        $manager->givePermissionTo('update settings');
        $manager->givePermissionTo('view sales');
        $manager->givePermissionTo('create sales');
        $manager->givePermissionTo('update sales');
        $manager->givePermissionTo('delete sales');
        $manager->givePermissionTo('view purchases');
        $manager->givePermissionTo('create purchases');
        $manager->givePermissionTo('update purchases');
        $manager->givePermissionTo('delete purchases');
        $manager->givePermissionTo('view products');
        $manager->givePermissionTo('create products');
        $manager->givePermissionTo('update products');
        $manager->givePermissionTo('delete products');
        $manager->givePermissionTo('view cashflows');
        $manager->givePermissionTo('update cashflows');
        $manager->givePermissionTo('delete cashflows');
        $manager->givePermissionTo('view customers');
        $manager->givePermissionTo('view users');
        $manager->givePermissionTo('create users');
        $manager->givePermissionTo('update users');
        $manager->givePermissionTo('delete users');
        $manager->givePermissionTo('view categories');
        $manager->givePermissionTo('create categories');
        $manager->givePermissionTo('update categories');
        $manager->givePermissionTo('delete categories');
        $manager->givePermissionTo('create customers');
        $manager->givePermissionTo('update customers');
        $manager->givePermissionTo('delete customers');
        $manager->givePermissionTo('view suppliers');
        $manager->givePermissionTo('create suppliers');
        $manager->givePermissionTo('update suppliers');
        $manager->givePermissionTo('delete suppliers');
        $manager->givePermissionTo('create permissions');
        $manager->givePermissionTo('update permissions');
        $manager->givePermissionTo('delete permissions');

        $seller->givePermissionTo('view sales');
        $seller->givePermissionTo('create sales');
        $seller->givePermissionTo('view cashflows');
        $seller->givePermissionTo('update cashflows');
        $seller->givePermissionTo('view customers');
        $seller->givePermissionTo('create customers');
        $seller->givePermissionTo('view products');

        $warehouseman->givePermissionTo('view suppliers');
        $warehouseman->givePermissionTo('create suppliers');
        $warehouseman->givePermissionTo('update suppliers');
        $warehouseman->givePermissionTo('view products');
        $warehouseman->givePermissionTo('create products');
        $warehouseman->givePermissionTo('update products');
        $warehouseman->givePermissionTo('view purchases');
        $warehouseman->givePermissionTo('create purchases');
        $warehouseman->givePermissionTo('update purchases');

        $reader->givePermissionTo('view products');
        $reader->givePermissionTo('view sales');
        $reader->givePermissionTo('view purchases');
        $reader->givePermissionTo('view users');

        $superAdmin->assignRole(Role::SUPER_ADMIN);
    }
}
