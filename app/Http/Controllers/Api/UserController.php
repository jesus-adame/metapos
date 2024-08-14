<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize(Permission::VIEW_USERS);

        $perPage = $request->input('rows', 10);
        $users = User::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        Gate::authorize(Permission::CREATE_USERS);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Correcto',
            'user' => $user,
        ]);
    }
}
