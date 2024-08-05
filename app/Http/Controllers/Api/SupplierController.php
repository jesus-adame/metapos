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
    public function index()
    {
        $suppliers = Supplier::all();
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
}
