<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\Branch;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;

class AuthenticatedApiController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        /** @var User */
        $user = Auth::user();

        // Asignar sucursal y caja por defecto si no están asignadas
        if (!$user->branch_id || !$user->cash_register_id) {
            /** @var Branch */
            $defaultBranch = Branch::where('is_default', true)->first();
            $defaultCashRegister = CashRegister::where('is_default', true)
                ->where('branch_id', $defaultBranch->id)
                ->first();

            $user->update([
                'location_id' => $defaultBranch->id,
                'location_type' => $defaultBranch::class,
                'cash_register_id' => $defaultCashRegister->id,
            ]);
        }

        // Generar token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolver el token al cliente
        return response()->json(['token' => $token]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Obtener el usuario autenticado
        $token = $request->user()->currentAccessToken();

        // Revocar el token del usuario
        $token->delete();

        return response()->json(['message' => 'Correcto.']);
    }
}
