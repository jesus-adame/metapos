<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize(Permission::VIEW_USERS);

        $perPage = $request->input('rows', 10);
        $users = User::with('roles')
            ->where('email', '!=', config('app.admin.email'))
            ->orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function store(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $roles = Role::where('name', $request->roles)->get();
        $user->syncRoles($roles);

        return response()->json([
            'message' => 'Usuario registrado correctamente.',
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;

        $roles = Role::whereIn('name', $request->roles)->get();
        $user->syncRoles($roles);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuario registrado correctamente.',
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }
}
