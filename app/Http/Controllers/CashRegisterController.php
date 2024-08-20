<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\Location;
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
        /** @var CashRegister */
        $cashRegister = CashRegister::find($request->cash_register_id);
        $location = Location::find($cashRegister->location->id);

        $user->update([
            'location_id' => $location->id,
            'location_type' => $location::class,
            'cash_register_id' => $cashRegister->id,
        ]);

        return response()->json([
            'mensage' => 'Cambiado correctamente',
            'cashRegister' => $cashRegister,
            'location' => $location,
        ]);
    }
}
