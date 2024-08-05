<?php

namespace App\Services\Users;

use App\Models\User;

class AllUsersService
{
    public function execute()
    {
        return User::all();
    }
}
