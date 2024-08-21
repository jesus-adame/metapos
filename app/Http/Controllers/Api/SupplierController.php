<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Permission;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize(Permission::VIEW_SUPPLIERS);

        $perPage = $request->input('rows', 10);
        $suppliers = Supplier::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($suppliers);
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        Gate::authorize(Permission::VIEW_SUPPLIERS);

        if (is_null($request->code)) {
            return response()->json([]);
        }

        $suppliers = Supplier::where('lastname', 'like', "%$request->code%")
            ->orWhere('email', 'like', "%$request->code%")
            ->orWhere('name', 'like', "%$request->code%")
            ->orWhere('phone', 'like', "%$request->code%")
            ->get();

        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        Gate::authorize(Permission::CREATE_SUPPLIERS);

        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Supplier::create($request->all());

        return response()->json([
            'message' => 'Proveedor registrado.'
        ]);
    }
}
