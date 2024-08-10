<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $suppliers = Supplier::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($suppliers);
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        if (is_null($request->code)) {
            return response()->json([]);
        }

        $suppliers = Supplier::where('last_name', 'like', "%$request->code%")
            ->orWhere('email', 'like', "%$request->code%")
            ->orWhere('first_name', 'like', "%$request->code%")
            ->orWhere('phone', 'like', "%$request->code%")
            ->get();

        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Supplier::create($request->all());

        return redirect()->route('users.index')->with('success', 'Supplier created successfully.');
    }
}
