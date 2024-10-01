<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function index()
    {
        Gate::authorize(Permission::VIEW_EXPENSES);

        return inertia('Expenses/Index');
    }
}
