<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CashRegister;
use App\Http\Controllers\Controller;

class CashRegisterController extends Controller
{
    public function select(Request $request)
    {
        $request->validate([
            'cash_register_id' => 'required'
        ]);

        /** @var User */
        $user = Auth::user();
        $cashRegister = CashRegister::find($request->cash_register_id);

        $user->update([
            'branch_id' => $cashRegister->branch->id,
            'cash_register_id' => $cashRegister->id,
        ]);

        $request->session()->regenerate();

        return response()->json([
            'mensage' => 'Cambiado correctamente'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:cash_registers,name',
            'branch_id' => 'required|integer|exists:branches,id'
        ]);

        $cashRegister = CashRegister::create($request->all());

        return response()->json([
            'message' => 'Registrado correctamente',
            'cashRegister' => $cashRegister,
        ]);
    }
}
