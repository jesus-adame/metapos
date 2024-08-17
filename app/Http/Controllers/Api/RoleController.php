<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize(Permission::VIEW_USERS);

        $roles = Role::with('permissions')->where('name', '!=', Role::SUPER_ADMIN)->paginate(10);

        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize(Permission::CREATE_PERMISSIONS);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return response()->json([
            'message' => 'Role registrado correctamente.',
            'role' => $role,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize(Permission::UPDATE_PERMISSION);

        $request->validate([
            'name' => 'required',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name' // Ejemplo: ["create products", "delete products", "create sales", "view purchases"]
        ]);

        $permissions = Permission::whereIn('name', $request->permissions)->get();

        $role->name = $request->name;
        $role->guard_name = 'web';
        $role->syncPermissions($permissions);

        $role->save();

        return response()->json([
            'message' => 'Role editado correctamente.',
            'role' => $role,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize(Permission::DELETE_PERMISSIONS);

        $role = DB::table('roles')->where('id', $id)->delete();

        if (!$role) {
            return response()->json([
                'message' => 'Role no encontrado.',
            ], 404);
        }

        return response()->json([
            'message' => 'Role eliminado correctamente.',
        ]);
    }
}
