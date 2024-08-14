<?php

namespace App\Models;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    const SUPER_ADMIN = 'Super Admin';

    const MANAGER = 'manager';

    const SELLER = 'seller';

    const WAREHOUSEMAN = 'warehouseman';

    const READER = 'reader';
}
