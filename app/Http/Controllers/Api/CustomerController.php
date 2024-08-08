<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $customers = Customer::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($customers);
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        if (is_null($request->code)) {
            return response()->json([]);
        }

        $customers = Customer::where('last_name', 'like', "%$request->code%")
            ->orWhere('email', 'like', "%$request->code%")
            ->orWhere('first_name', 'like', "%$request->code%")
            ->orWhere('phone', 'like', "%$request->code%")
            ->get();

        return response()->json($customers);
    }
}
