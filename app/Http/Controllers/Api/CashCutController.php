<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CashCut;
use App\Http\Controllers\Controller;

class CashCutController extends Controller
{
    public function index(Request $request)
    {
        $cashRegister = auth()->user()->cashRegister;

        $perPage = $request->input('rows', 10);
        $cashCuts = CashCut::with('cashRegister')
            ->where('cash_register_id', $cashRegister->id)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return response()->json($cashCuts);
    }
}
