<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $users = Branch::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }
}
