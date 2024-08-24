<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\CashRegister;
use App\Http\Controllers\Controller;

class CashRegisterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('rows', 10);
        $cashRegisters = CashRegister::with('location')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return response()->json($cashRegisters);
    }

    public function search(Request $request): JsonResponse
    {
        $params = $request->params;
        $cashRegisters = CashRegister::with('location')
            ->orderBy('updated_at', 'desc')
            ->where($params)
            ->limit(10)
            ->get();

        return response()->json($cashRegisters);
    }

    public function select(Request $request): JsonResponse
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
            'cash_register_id' => $cashRegister->id,
        ]);

        return response()->json([
            'mensage' => 'Cambiado correctamente',
            'cashRegister' => $cashRegister,
            'location' => $location,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:cash_registers,name',
            'location_id' => 'required|integer|exists:locations,id'
        ]);

        $cashRegister = CashRegister::create([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'is_default' => false,
        ]);

        return response()->json([
            'message' => 'Registrado correctamente',
            'cashRegister' => $cashRegister,
        ]);
    }

    public function destroy(CashRegister $cashRegister): JsonResponse
    {
        if ($cashRegister->is_default) {
            return response()->json([
                'message' => 'No se pueden eliminar datos por defecto.'
            ], JsonResponse::HTTP_BAD_GATEWAY);
        }

        $cashRegister->delete();

        return response()->json([
            'message' => 'Eliminado correctamente.'
        ]);
    }
}
