<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CashRegister;
use App\Http\Controllers\Controller;

class CashRegisterController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $cashRegisters = CashRegister::with('branch')->orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($cashRegisters);
    }

    public function search(Request $request)
    {
        $params = $request->params;
        $cashRegisters = CashRegister::with('branch')
            ->orderBy('updated_at', 'desc')
            ->where($params)
            ->limit(10)
            ->get();

        return response()->json($cashRegisters);
    }
}
