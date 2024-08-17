<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Role::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard' => $request->guard,
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
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
