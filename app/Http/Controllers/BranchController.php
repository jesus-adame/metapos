<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return inertia('Branches/Index', ['branches' => $branches]);
    }

    public function create()
    {
        return inertia('Branches/Create');
    }

    public function edit(Branch $branch)
    {
        return inertia('Branches/Edit', ['branch' => $branch]);
    }
}
