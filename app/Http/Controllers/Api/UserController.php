<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $users = User::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }
}
